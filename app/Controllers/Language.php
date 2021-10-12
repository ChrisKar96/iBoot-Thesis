<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function index()
    {
        $session = session();
        $locale  = $this->request->getLocale();
        $session->remove('lang');
        $session->set('lang', $locale);

        return redirect()->to($this->request->getUserAgent()->getReferrer());
    }
}
