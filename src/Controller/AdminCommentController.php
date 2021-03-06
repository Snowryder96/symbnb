<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment")
     */
    public function index(CommentRepository $repo)
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll(),
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     */
    public function delete(Comment $comment, EntityManagerInterface $manager)
    {

        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire numero{$comment->getId()} a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comment');
    }
    /**
     * Permet de modifier un commentaire
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * 
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce a bien été modifié"
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
}
