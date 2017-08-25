<?php

namespace MP\PicturesBundle\Controller;

use MP\PicturesBundle\Entity\Image;
use MP\PicturesBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PicturesController extends Controller
{
    public function indexAction()
    {
    
      $em = $this->getDoctrine()->getManager();

      $listImages = $em->getRepository('MPPicturesBundle:Image')->findAll();

      return $this->render('MPPicturesBundle:Pictures:index.html.twig', array(
        'listImages' => $listImages
      ));
    }
    
		/**
     * @Security("has_role('ROLE_ADMIN')")
   	 */
    public function uploadAction(Request $request)
    {
      $image = new Image();
      $form= $this->get('form.factory')->create(ImageType::class, $image);
      
      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();

        return $this->redirectToRoute('mp_pictures_homepage');
      }
			      
			return $this->render('MPPicturesBundle:Pictures:upload.html.twig', array(
        'form' => $form->createView(),
      ));
    }
	
		/**
     * @Security("has_role('ROLE_ADMIN')")
   	 */
		public function removeAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $image = $em->getRepository('MPPicturesBundle:Image')->find($id);

    if (null === $image) {
      throw new NotFoundHttpException("L'image d'id ".$id." n'existe pas.");
    }
			
		$form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($image);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', "L'image a bien été supprimée.");

      return $this->redirectToRoute('mp_pictures_homepage');
    }
    
    return $this->render('MPPicturesBundle:Pictures:remove.html.twig', array(
      'image' => $image,
      'form'   => $form->createView(),
    ));
  }
}
