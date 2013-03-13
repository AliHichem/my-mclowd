<?php

namespace MC\AssetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\File\File,
    Symfony\Component\HttpFoundation\File\UploadedFile,
    Symfony\Component\HttpFoundation\Response,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class UploadController extends Controller
{

    public function uploadAction()
    {
        $file = $this->get('request')->files->get('qqfile');
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return new Response(json_encode(array(
                'error' => 'Could not save uploader file'                
            )));
        }

        $return = $this->get('mc.asset_manager')->upload($file);
        $return['success'] = true;
        return new Response(
            json_encode($return)
        );
        
    }
}
