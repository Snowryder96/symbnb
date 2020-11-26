<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * Permet d'afficher les annonces
     * 
     * @Route("/ads", name="ads_index")
     * 
     * @param AdRepository $repo
     * @return Response
     */
    public function index(AdRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Ad::class);

        $ads = $repo->findAll();
        return $this->render('ad/index.html.twig', [
            'ads'=> $ads
        ]);
    }
    /**
     * Permet de crée une annoce
     * 
     * @Route("/ads/new", name="ads_create")
     * @IsGranted("ROLE_USER")
     * 
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){

        $ad = new Ad();

        $form = $this->createForm(AdType:: class, $ad);

        $form->handleRequest($request);

        

        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "l'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' =>$ad->getSlug()
            ]);
        }
        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet d'afficher le formulaire d'edition
     * 
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier !")
     * 
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(AdType:: class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image){
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' =>$ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(), 
            'ad' => $ad
        ]);
    }
    /**
     * Permet d'afficher une seule annonce
     *
     * @Route("/ads/{slug}", name="ads_show")
     * 
     * @param Ad $ad
     * @return response
     */
    public function show(Ad $ad){
        return $this->render('ad/show.html.twig',
        [
            "ad" => $ad
        ]);
    }
    

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @Security("is_granted('ROLE_USER') and user == ad.getAuthor()", message="Vous n'avez pas le droit d'acceder a cette ressource")
     *
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager){
        $manager->remove($ad);
        $manager->flush();

        $this->addFlash(
            'success',
            "l'annonce <strong>{$ad->getTitle()}</strong> a bien ete supprimer !"
        );

        return $this->redirectToRoute("ads_index");
    }
    
}
