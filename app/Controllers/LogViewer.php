<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Controllers;

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use Exception;

class LogViewer extends BaseController
{
    public const LOG_LINE_HEADER_PATTERN = '/^([A-Z]+)\s*-\s*([\-\d]+\s+[:\d]+)\s*-->\s*(.+)$/';
    public const MAX_LOG_SIZE            = 52428800; // 50MB
    public const MAX_STRING_LENGTH       = 300; // 300 chars

    /**
     * These are the constants representing the
     * various API commands there are
     */
    private const API_QUERY_PARAM = 'api';

    private const API_FILE_QUERY_PARAM      = 'f';
    private const API_LOG_STYLE_QUERY_PARAM = 'sline';
    public const API_CMD_LIST               = 'list';
    private const API_CMD_VIEW              = 'view';
    private const API_CMD_DELETE            = 'delete';

    private static array $levelsIcon = [
        'DEBUG'     => 'fa-solid fa-triangle-exclamation',
        'INFO'      => 'fa-solid fa-circle-info',
        'NOTICE'    => 'fa-solid fa-flag',
        'WARNING'   => 'fa-solid fa-circle-exclamation',
        'ERROR'     => 'fa-solid fa-xmark',
        'CRITICAL'  => 'fa-solid fa-bug',
        'ALERT'     => 'fa-solid fa-triangle-exclamation',
        'EMERGENCY' => 'fa-solid fa-dumpster-fire',
    ];
    private static array $levelClasses = [
        'DEBUG'     => 'warning',
        'INFO'      => 'info',
        'NOTICE'    => 'warning',
        'WARNING'   => 'warning',
        'ERROR'     => 'danger',
        'CRITICAL'  => 'danger',
        'ALERT'     => 'danger',
        'EMERGENCY' => 'danger',
    ];

    // this is the path (folder) on the system where the log files are stored
    private string $logFolderPath = WRITEPATH . 'logs/';

    // this is the pattern to pick all log files in the $logFilePath
    private string $logFilePattern = 'log-*.log';

    // this is a combination of the LOG_FOLDER_PATH and LOG_FILE_PATTERN
    private string $fullLogFilePath = '';

    /**
     * Name of the view to pass to the renderer
     * Note that it allows namespaced views if your view is outside
     * the View folder.
     */
    private string $viewName = 'iBoot\\Views\\logs';

    public function index()
    {
        return $this->showLogs();
    }

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Bootstrap the library
     * sets the configuration variables
     *
     * @throws Exception
     */
    private function init()
    {
        $viewerConfig = config('Logger');

        // configure the log folder path and the file pattern for all the logs in the folder
        if ($viewerConfig) {
            if (isset($viewerConfig->viewName)) {
                $this->viewName = $viewerConfig->viewName;
            }
            if (isset($viewerConfig->logFilePattern)) {
                $this->logFilePattern = $viewerConfig->logFilePattern;
            }
            if (isset($viewerConfig->logFolderPath)) {
                $this->logFolderPath = $viewerConfig->logFolderPath;
            }
        }

        // concatenate to form Full Log Path
        $this->fullLogFilePath = $this->logFolderPath . $this->logFilePattern;
    }

    /**
     * This function will return the processed HTML page
     * and return it's content that can then be echoed
     *
     * @return false|RedirectResponse|string
     */
    public function showLogs()
    {
        $request = Services::request();

        if (null !== $request->getGet('del')) {
            $this->deleteFiles(base64_decode($request->getGet('del'), true));
            $uri = Services::request()->uri->getPath();

            return redirect()->to('/' . $uri);
        }

        // process download of log file command
        // if the supplied file exists, then perform download
        // otherwise, just ignore which will resolve to page reloading
        $dlFile = $request->getGet('dl');
        if (null !== $dlFile && file_exists($this->logFolderPath . basename(base64_decode($dlFile, true)))) {
            $file = $this->logFolderPath . basename(base64_decode($dlFile, true));
            $this->downloadFile($file);
        }

        if (null !== $request->getGet(self::API_QUERY_PARAM)) {
            return $this->processAPIRequests($request->getGet(self::API_QUERY_PARAM));
        }

        // it will either get the value of f or return null
        $fileName = $request->getGet('f');

        // get the log files from the log directory
        $files = $this->getFiles();

        // let's determine what the current log file is
        if (null !== $fileName) {
            $currentFile = $this->logFolderPath . basename(base64_decode($fileName, true));
        } elseif (! empty($files)) {
            $currentFile = $this->logFolderPath . $files[0];
        } else {
            $currentFile = null;
        }

        // if the resolved current file is too big
        // just trigger a download of the file
        // otherwise process its content as log

        if (null !== $currentFile && file_exists($currentFile)) {
            $fileSize = filesize($currentFile);

            if (is_int($fileSize) && $fileSize > self::MAX_LOG_SIZE) {
                // trigger a download of the current file instead
                $logs = null;
            } else {
                $logs = $this->processLogs($this->getLogs($currentFile));
            }
        } else {
            $logs = [];
        }

        $data['logs']        = $logs;
        $data['files']       = ! empty($files) ? $files : [];
        $data['currentFile'] = null !== $currentFile ? basename($currentFile) : '';
        $data['title']       = lang('Text.log_viewer');
        $data['tabulator']   = true;

        return view($this->viewName, $data);
    }

    public function processAPIRequests($command = self::API_CMD_LIST, $file = null)
    {
        $request = Services::request();
        if ($command === self::API_CMD_LIST) {
            // respond with a list of all the files
            $response = $this->getFilesBase64Encoded();
        } elseif ($command === self::API_CMD_VIEW) {
            // respond to view the logs of a particular file
            // $file                  = $request->getGet(self::API_FILE_QUERY_PARAM);
            // $response['log_files'] = $this->getFilesBase64Encoded();
            $fileName   = basename(base64_decode($file, true));
            $fileExists = file_exists($this->logFolderPath . $fileName);
            if (! empty($file) && ! empty($fileName) && $fileExists) {
                $singleLine = $request->getGet(self::API_LOG_STYLE_QUERY_PARAM);
                $singleLine = $singleLine === true || $singleLine === 'true' || $singleLine === '1';
                $logs       = $this->processLogsForAPI($file, $singleLine);
                $response   = $logs;
            } else {
                $response['error']['message'] = 'Invalid File Name Supplied: [' . json_encode($file) . ']';
                $response['error']['code']    = 400;
            }
        } elseif ($command === self::API_CMD_DELETE) {
            // $file = $request->getGet(self::API_FILE_QUERY_PARAM);

            if (null === $file) {
                $response['error']['message'] = 'NULL value is not allowed for file param';
                $response['error']['code']    = 400;
            } else {
                // decode file if necessary

                if ($file !== 'all') {
                    $file       = basename(base64_decode($file, true));
                    $fileExists = file_exists($this->logFolderPath . $file);
                } else {
                    // check if the directory exists
                    $fileExists = file_exists($this->logFolderPath);
                }

                if (! empty($file) && $fileExists) {
                    $this->deleteFiles($file);
                    $response['message'] = 'File [' . $file . '] deleted';
                } else {
                    $response['error']['message'] = 'File does not exist';
                    $response['error']['code']    = 404;
                }
            }
        } else {
            $response['error']['message'] = 'Unsupported Query Command [' . $command . ']';
            $response['error']['code']    = 400;
        }

        // convert response to json and respond
        header('Content-Type: application/json');
        if (! empty($response['error'])) {
            // set a generic bad request code
            http_response_code($response['error']['code']);
        } else {
            http_response_code(200);
        }

        return json_encode($response);
    }

    /**
     * This function will process the logs. Extract the log level, icon class and other information
     * from each line of log and then arrange them in another array that is returned to the view for processing
     *
     * @params logs. The raw logs as read from the log file
     *
     * @param mixed $logs
     *
     * @return array. An [[], [], [] ...] where each element is a processed log line
     * */
    private function processLogs($logs): ?array
    {
        if (null === $logs) {
            return null;
        }

        $superLog = [];

        foreach ($logs as $log) {
            if ($this->getLogHeaderLine($log, $level, $logDate, $logMessage)) {
                // this is actually the start of a new log and not just another line from previous log
                $data = [
                    'level' => $level,
                    'date'  => $logDate,
                    'icon'  => self::$levelsIcon[$level],
                    'class' => self::$levelClasses[$level],
                ];

                if (strlen($logMessage) > self::MAX_STRING_LENGTH) {
                    $data['content'] = substr($logMessage, 0, self::MAX_STRING_LENGTH);
                    $data['extra']   = substr($logMessage, (self::MAX_STRING_LENGTH + 1));
                } else {
                    $data['content'] = $logMessage;
                }

                $superLog[] = $data;
            } elseif (! empty($superLog)) {
                // this log line is a continuation of previous logline
                // so let's add them as extra
                $prevLog                        = $superLog[count($superLog) - 1];
                $extra                          = (array_key_exists('extra', $prevLog)) ? $prevLog['extra'] . '<br>' : '';
                $prevLog['extra']               = $extra . $log;
                $superLog[count($superLog) - 1] = $prevLog;
            }
        }

        return $superLog;
    }

    /**
     * This function will extract the logs in the supplied
     * fileName
     *
     * @param mixed $fileNameInBase64
     *
     * @return bool|string|null
     *
     * @internal param $logs
     */
    private function processLogsForAPI($fileNameInBase64, bool $singleLine = false)
    {
        $logs = null;

        // let's prepare the log file name sent from the client
        $currentFile = $this->prepareRawFileName($fileNameInBase64);

        // if the resolved current file is too big
        // just return null
        // otherwise process its content as log
        if (null !== $currentFile) {
            $fileSize = filesize($currentFile);

            if (! (is_int($fileSize) && $fileSize > self::MAX_LOG_SIZE)) {
                $logs = $this->getLogsForAPI($currentFile, $singleLine);
            }
        }

        // trigger a download of the current file instead
        return $logs;
    }

    private function getLogHeaderLine($logLine, &$level, &$dateTime, &$message): array
    {
        $matches = [];
        if (preg_match(self::LOG_LINE_HEADER_PATTERN, $logLine, $matches)) {
            $level    = $matches[1];
            $dateTime = $matches[2];
            $message  = $matches[3];
        }

        return $matches;
    }

    /**
     * returns an array of the file contents
     * each element in the array is a line
     * in the underlying log file
     *
     * @returns array | each line of file contents is an entry in the returned array.
     *
     * @params complete fileName
     *
     * @param mixed $fileName
     * */
    private function getLogs($fileName)
    {
        $size = filesize($fileName);
        if (! $size || $size > self::MAX_LOG_SIZE) {
            return null;
        }

        $logs          = [];
        $log_array     = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $log_array_num = count($log_array);

        for ($i = 0; $i < $log_array_num; $i++) {
            if (! str_contains($log_array[$i], 'Session: Class initialized using')) {
                $logs[] = $log_array[$i];
            }
        }

        return $logs;
    }

    /**
     * This function will get the contents of the log
     * file as a string. It will first check for the
     * size of the file before attempting to get the contents.
     *
     * By default it will return all the log contents as an array where the
     * elements of the array is the individual lines of the files
     * otherwise, it will return all file content as a single string with each line ending
     * in line break character "\n"
     *
     * @param mixed $fileName
     *
     * @return bool|string
     */
    private function getLogsForAPI($fileName, bool $singleLine = false)
    {
        $size = filesize($fileName);
        if (! $size || $size > self::MAX_LOG_SIZE) {
            return 'File Size too Large. Please download it locally';
        }

        if (! $singleLine) {
            $logs          = [];
            $log_array     = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $log_array_num = count($log_array);

            for ($i = 0; $i < $log_array_num; $i++) {
                if (! str_contains($log_array[$i], 'Session: Class initialized using')) {
                    $logs[] = $log_array[$i];
                }
            }

            return $logs;
        }

        return file_get_contents($fileName);

    }

    /**
     * This will get all the files in the logs folder
     * It will reverse the files fetched and
     * make sure the latest log file is in the first index
     *
     * @param bool $basename If true returns the basename of the files otherwise full path
     *
     * @returns array of file
     * */
    private function getFiles(bool $basename = true)
    {
        $files = glob($this->fullLogFilePath);

        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');
        if ($basename) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }

        return array_values($files);
    }

    /**
     * This function will return an array of available log
     * files
     * The array will contain the base64encoded name
     * as well as the real name of the file
     *
     * @internal param bool $appendURL
     * @internal param bool $basename
     */
    private function getFilesBase64Encoded(): array
    {
        $files = glob($this->fullLogFilePath);

        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');

        $finalFiles = [];

        // if we're to return the base name of the files
        // let's do that here
        foreach ($files as $file) {
            $finalFiles[] = ['file_b64' => base64_encode(basename($file)), 'file_name' => basename($file)];
        }

        return $finalFiles;
    }

    /**
     * Delete one or more log file in the logs directory
     *
     * @param mixed $fileName It can be all - to delete all log files - or specific for a file
     */
    private function deleteFiles($fileName)
    {
        if ($fileName === 'all') {
            array_map('unlink', glob($this->fullLogFilePath));
        } else {
            unlink($this->logFolderPath . basename($fileName));
        }
    }

    /**
     * Download a particular file to local disk
     * This should only be called if the file exists
     * hence, the file exist check has ot be done by the caller
     *
     * @param mixed $file the complete file path
     */
    private function downloadFile($file)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);

        exit;
    }

    /**
     * This function will take in the raw file
     * name as sent from the browser/client
     * and append the LOG_FOLDER_PREFIX and decode it from base64
     *
     * @internal param $fileName
     *
     * @param mixed $fileNameInBase64
     */
    private function prepareRawFileName($fileNameInBase64): ?string
    {
        // let's determine what the current log file is
        if (! empty($fileNameInBase64)) {
            $currentFile = $this->logFolderPath . basename(base64_decode($fileNameInBase64, true));
        } else {
            $currentFile = null;
        }

        return $currentFile;
    }
}
