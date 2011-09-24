<?php

namespace Ivory\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ivory\DemoBundle\Entity\File;
use Ivory\DemoBundle\Form\FileType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Demo controller
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class DemoController extends Controller
{
    /**
     * @Route("/", name="demo")
     * @Template()
     */
    public function demoAction()
    {
        $file = new File();
        $form = $this->createForm(new FileType(), $file);
        
        if($this->getRequest()->getMethod() === 'POST')
        {
            $form->bindRequest($this->getRequest());
            
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($file);
                $em->flush();
                
                $this->get('session')->setFlash('notice', 'The file has been upload !');
                
                return $this->redirect($this->generateUrl('demo'));
            }
        }
        
        return array('form' => $form->createView());
    }
}
