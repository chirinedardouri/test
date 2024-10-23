<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ClasseRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Classe;
use App\Form\ClasseType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ClasseController extends AbstractController
{
    #[Route('/classe', name: 'app_classe')]
    public function index(): Response
    {
        return $this->render('classe/index.html.twig', [
            'controller_name' => 'ClasseController',
        ]);
    }

    #[Route('/ListeClasse', name: 'app_listeClasse')]
    public function listClasse(ClasseRepository $repository): Response
    {
        $classe=$repository->findAll(); 
        return $this->render('/classe/listeClasse.html.twig', ['classes' => $classe]);
    }
    #[Route('/addClasse', name: 'app_AddClasse')]
    public function Add(Request $request, EntityManagerInterface $em): Response
    {
        $classe= new Classe();
        $form = $this->createForm(ClasseType::class, $classe);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid() ) {
           
          
            $em->persist($classe);
            $em->flush();
    
            
            $this->addFlash('success', 'la classe a été ajouté avec succès !');
    
            return $this->redirectToRoute('app_listeClasse');
        }
       
    
        return $this->render('classe/Add.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    
    
    #[Route('/editClasse/{id}', name: 'app_editClasse')]
    public function edit(ClasseRepository $repository, $id, Request $request, EntityManagerInterface $em): Response
    {
        $classe = $repository->find($id);
        if (!$classe) {
            throw $this->createNotFoundException('classe non trouvé');
        }
    
        $form = $this->createForm(ClasseType::class, $classe);
        $form->add('Edit', SubmitType::class);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
    
            // Message flash de succès
            $this->addFlash('success', 'La classe est modifié avec succes !');
    
            return $this->redirectToRoute("app_listeClasse");
        }
    
        return $this->render('classe/edit.html.twig', [
            'f' => $form->createView(),
        ]);
    }

    #[Route('/deleteClasse/{id}', name: 'app_delete')]
    public function delete($id, ClasseRepository $repository, EntityManagerInterface $em): Response
    {
        $classe = $repository->find($id);
    
        if (!$classe) {
            throw $this->createNotFoundException(' non trouvé');
        }
    
        $em->remove($classe);
        $em->flush();
    
        return $this->redirectToRoute('app_listeClasse');
    }
    

}
