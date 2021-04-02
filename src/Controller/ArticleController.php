<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\CategorieArticle;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('article/article.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    /**
     * @Route("/article/affiche",name="affichercatarticle")
     */
    public function affiche(){
        $repo=$this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/affiche.html.twig',['article'=>$repo]);
    }
    /**
     * @Route("/article/details/{id}",name="detailscatarticle")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/details.html.twig',['article'=>$repo]);
    }
    /**
     * @Route("/catarticle/{id}", name="catarticle")
     */
    public function montrer($id){
        $article=$this->getDoctrine()->getRepository(Article::class)->find($id);
        $categorie=$this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        $em=$this->getDoctrine()->getManager();
        $con=$em->getConnection();
        $sql='UPDATE article SET nbvue=nbvue+1 WHERE id='.$id;
        $stmt = $con -> prepare($sql);
        $stmt -> execute();
        return $this->render('article/index.html.twig',['article'=>$article, 'categories'=>$categorie]);





    }

    /**
     * @Route("/blog",name="afficherblog")
     */
    public function afficheblog(Request $request, PaginatorInterface $paginator , ArticleRepository $repository){
        $categorie = $this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        $donnees = $this->getDoctrine()->getRepository(Article::class)->findBy([],
        ['created_at'=>'desc']);
        $top=$this->getDoctrine()->getRepository(Article::class)->recommended();
        $article =$paginator->paginate(
            $donnees,
            $request->query->getInt('page',1),
            2
        );

        return $this->render('blog/blog.html.twig',['articles'=>$article , 'categories'=>$categorie , 'top'=>$top]);
    }


    /**
     * @Route("article/delete/{id}",name="deletecatarticle")
     */

    public function delete($id,ArticleRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $article=$repo->find($id);
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('affichercatarticle');
    }
    /**
     * @Route("/article/ajouter",name="Ajoutercatarticle")
     */
    function Ajout(Request $request){
        $article=new Article();
        $form=$this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($article);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("affichercatarticle");
        }
        return $this->render('article/ajout.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("article/update/{id}",name="updatecatarticle")
     * @param $id
     * @param ArticleRepository $repo
     * @param Request $request
     * @param $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    function update($id, ArticleRepository $repo, Request $request){
        $article=$repo->find($id) ;
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('affichercatarticle');
        }

        return $this->render('article/update.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route ("/article/like/{id}", name="jaime")
     * @param $id
     * @param ArticleRepository $repo
     * @param $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function like($id, ArticleRepository $repo ){
        $em = $this->getDoctrine()->getManager();
        $article = $repo->find($id);
        $categorie=$this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();

        $article->setNblike($article->getNblike()+1);
        $em->flush();
        return $this->render("article/index.html.twig" , ['article' => $article,'categories'=>$categorie]);
    }

    /**
     * @Route ("/article/dislike/{id}", name="jaimepas")
     * @param $id
     * @param ArticleRepository $repo
     * @param $article
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function dislike($id, ArticleRepository $repo ){
        $em = $this->getDoctrine()->getManager();
        $article = $repo->find($id);
        $categorie=$this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        $article->setNbdislike($article->getNbdislike()+1);
        $em->flush();
        return $this->render("article/index.html.twig" , ['article' => $article,'categories'=>$categorie]);
    }




}
