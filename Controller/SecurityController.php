<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        //formulaire: analyse la requete que tu passe ici :handleRequest()
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user-> setPassword($hash);
            //je vaux que tu fasse persister dans le temps le $user (dans la DB) 
            // ou prépare toi à le sauvegarder dans la DB 
            $manager->persist($user);
            //fais le maintenant réelement avec flush()
            $manager->flush();

            return $this-> redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
        ]);
       
    }

    /**
     * @route("/connexion", name="security_login")
     */

    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @route("/deconnexion", name="security_logout")
     */
    public function logout(){
      //  return $this->render('security/login.html.twig');
    }


}
