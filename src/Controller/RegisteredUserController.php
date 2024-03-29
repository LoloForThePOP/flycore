<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PersorgType;
use App\Form\EmailFormType;
use App\Service\ImageResizer;
use App\Form\DeleteEntityType;
use App\Form\UpdatePasswordType;
use App\Service\RemovePresentation;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisteredUserController extends AbstractController
{
    /**
     * @Route("/user/profile/{userNameSlug}", name="show_user_profile")
     */
    public function showProfile(User $user): Response
    {
        return $this->render('user/show_profile.html.twig', [

            'user' => $user,

        ]);
    }

    /**
     * Allow user to manage its public profile
     * 
     * @Route("account/public-profile/", name="edit_public_profile")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function publicProfile(Request $request, EntityManagerInterface $manager, ImageResizer $imageResizer){

        $userPersorg = $this->getUser()->getPersorg();

        $persorgForm = $this->createForm(PersorgType::class, $userPersorg); 

        $persorgForm->handleRequest($request);

        if($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $this->getUser()->setUserName($persorgForm->get('name')->getData());

            $manager->flush();

            $imageResizer->edit($userPersorg);

            $this->addFlash(
                'success',
                'Les Modifications ont été enregistrées.'
            );

            return $this->redirectToRoute('show_user_profile',[
                'userNameSlug' => $this->getUser()->getUserNameSlug(),
            ]);
        }

        return $this->render('/user/edit_persorg.html.twig',[
     
            'persorgForm' => $persorgForm->createView(),
            'userPersorg' => $userPersorg,
        ]);

    }


      /**
     * Allow user to access update account possibilities menu
     * 
     * @Route("user/account/update-menu",name="update_account_menu")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function accessUpdateAccountMenu(){

        return $this->render('user/update_account_menu.html.twig',[
        ]);
    }


    
    /**
     * Allow user to modify his account email
     * 
     * @Route("account/update-email", name="update_account_email")
     * 
     * @return Response
     */
    public function updateEmail(Request $request, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $form = $this->createForm(EmailFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'success',
                'Adresse e-mail modifiée.'
            );

            return $this->redirectToRoute('show_user_profile',[
                'userNameSlug' => $this->getUser()->getUserNameSlug(),
            ]);
        }

        return $this->render('user/update_account_email.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Allow user to update his password
     * 
     * @Route("/user/account/update-password",name="update_account_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordHasherInterface $hasher, EntityManagerInterface $manager){
        
        $user = $this->getUser();
        
        $form=$this->createForm(UpdatePasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // check if user has given his current correct password
            
            if (!$hasher->isPasswordValid(
                    $user, 
                    $form->get('oldPassword')->getData()
                )
            ){
                $form->get('oldPassword')->addError(new FormError("Il faut écrire ici votre mot de passe actuel"));

            }else{ //set new password

                $newPassword = $form->get('newPassword')->getData();
                $hash = $hasher->hashPassword($user, $newPassword);
                $user->setPassword($hash);

                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a été modifié avec succès'
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('user/update_account_password.html.twig',[
            'form' => $form->createView(),
            
        ]);

    }


    
    /**
     * Allow user to delete her account
     * 
     * @Route("/user/account/delete", name="delete_account")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return Response
     */
    public function deleteAccount(Request $request, EntityManagerInterface $manager, RemovePresentation $deletePresentationService){
        
        $form=$this->createForm(DeleteEntityType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            foreach ($user->getCreatedPresentations() as $presentation) {
                $deletePresentationService->removePresentation($presentation);
            }

            $manager->remove($user);

            $manager->flush($user);

            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
                   
            $this->addFlash(
                'success',
                'Votre compte a été supprimé.'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/delete_account.html.twig',[
            'form' => $form->createView(),
            
        ]);

    }





    
}
