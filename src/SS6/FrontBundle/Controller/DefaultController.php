<?php

namespace SS6\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

	public function indexAction($name) {
		return $this->render('SS6FrontBundle:Default:index.html.twig', array('name' => $name));
	}

}
