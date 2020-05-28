<?php 
namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController {
    /**
     * @Route("/bonjour/{prenom}/{age}", name="hello" )
     * @Route("/bonjour", name="hello_base" )
     * @Route("/bonjour/{prenom}", name="hello_prenom" )
     * montre la page bonjour
     *
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0){
        return $this->render(
            'hello.html.twig',
            [
                'prenom'=> $prenom,
                'age'=>$age
            ]);
    }
    /**
     * @Route("/", name="homepage")
     */
    public function home(){
        $prenoms = ["alexis"=>30, "joe"=>12, "bob"=>9];

        return $this->render(
            'home.html.twig',
            [
                'title'=> "Bonjour a tous",
                'age'=> "14",
                'prenoms' => $prenoms
            
            ]
        );
    }
}

?>