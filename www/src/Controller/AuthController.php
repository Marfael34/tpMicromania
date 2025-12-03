<?php

/**
 * ============================================
 * AUTH CONTROLLER
 * ============================================
 * 
 * Contrôleur pour l'authentification (login, register, logout)
 * Utilise AuthManager pour gérer l'authentification
 */

declare(strict_types=1);

namespace App\Controller;

use JulienLinard\Core\Controller\Controller;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Auth\Middleware\GuestMiddleware;
use JulienLinard\Auth\Middleware\AuthMiddleware;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Core\Form\Validator;
use JulienLinard\Core\Session\Session;
use App\Entity\User;
use App\Repository\UserRepository;

class AuthController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private EntityManager $em,
        private Validator $validator
    ) {}
    
    /**
     * Affiche le formulaire de connexion
     * 
     * CONCEPT : Route protégée par GuestMiddleware (seulement pour les utilisateurs non authentifiés)
     */
    #[Route(path: '/login', methods: ['GET'], name: 'login', middleware: [new GuestMiddleware()])]
    public function loginForm(): Response
    {
        return $this->view('auth/login', [
            'title' => 'Connexion'
        ]);
    }
    
    /**
     * Traite la connexion
     * 
     * CONCEPT : Validation des données, tentative d'authentification
     */
    #[Route(path: '/login', methods: ['POST'], name: 'login.post', middleware: [new GuestMiddleware()])]
    public function login(Request $request): Response
    {
        $email = $request->getBodyParam('email', '');
        $password = $request->getBodyParam('password', '');
        $remember = $request->getBodyParam('remember', false);
        
        // Validation
        $errors = [];
        
        if (!$this->validator->required($email)) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!$this->validator->email($email)) {
            $errors['email'] = 'L\'email n\'est pas valide';
        }
        
        if (!$this->validator->required($password)) {
            $errors['password'] = 'Le mot de passe est requis';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');
            return $this->view('auth/login', [
                'title' => 'Connexion',
                'errors' => $errors,
                'old' => ['email' => $email]
            ]);
        }
        
        // Tentative d'authentification
        $credentials = [
            'email' => $email,
            'password' => $password
        ];
        
        if ($this->auth->attempt($credentials, (bool)$remember)) {
            Session::flash('success', 'Connexion réussie !');
            return $this->redirect('/');
        }
        
        Session::flash('error', 'Email ou mot de passe incorrect');
        return $this->view('auth/login', [
            'title' => 'Connexion',
            'old' => ['email' => $email]
        ]);
    }
    
    /**
     * Affiche le formulaire d'inscription
     * 
     * CONCEPT : Route protégée par GuestMiddleware
     */
    #[Route(path: '/register', methods: ['GET'], name: 'register', middleware: [new GuestMiddleware()])]
    public function registerForm(): Response
    {
        return $this->view('auth/register', [
            'title' => 'Inscription'
        ]);
    }
    
    /**
     * Traite l'inscription
     * 
     * CONCEPT : Validation, vérification de l'unicité de l'email, création de l'utilisateur
     */
    #[Route(path: '/register', methods: ['POST'], name: 'register.post', middleware: [new GuestMiddleware()])]
    public function register(Request $request): Response
    {
        $email = $request->getBodyParam('email', '');
        $password = $request->getBodyParam('password', '');
        $passwordConfirm = $request->getBodyParam('password_confirm', '');
        $firstname = $request->getBodyParam('firstname', '');
        $lastname = $request->getBodyParam('lastname', '');
        
        // Validation
        $errors = [];
        
        if (!$this->validator->required($email)) {
            $errors['email'] = 'L\'email est requis';
        } elseif (!$this->validator->email($email)) {
            $errors['email'] = 'L\'email n\'est pas valide';
        } else {
            // Vérifier si l'email existe déjà
            $userRepo = $this->em->createRepository(UserRepository::class, User::class);
            if ($userRepo->emailExists($email)) {
                $errors['email'] = 'Cet email est déjà utilisé';
            }
        }
        
        if (!$this->validator->required($password)) {
            $errors['password'] = 'Le mot de passe est requis';
        } elseif (!$this->validator->min($password, 8)) {
            $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
        }
        
        if (!$this->validator->required($passwordConfirm)) {
            $errors['password_confirm'] = 'La confirmation du mot de passe est requise';
        } elseif ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Les mots de passe ne correspondent pas';
        }
        
        if (!$this->validator->required($firstname)) {
            $errors['firstname'] = 'Le prénom est requis';
        }
        
        if (!$this->validator->required($lastname)) {
            $errors['lastname'] = 'Le nom est requis';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');
            return $this->view('auth/register', [
                'title' => 'Inscription',
                'errors' => $errors,
                'old' => [
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname
                ]
            ]);
        }
        
        // Créer l'utilisateur
        try {
            $user = new User();
            $user->email = $email;
            $user->password = password_hash($password, PASSWORD_BCRYPT);
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->role = 'user';
            $user->created_at = new \DateTime();
            $user->updated_at = new \DateTime();
            
            $this->em->persist($user);
            $this->em->flush();
            
            // Connecter automatiquement l'utilisateur après l'inscription
            $this->auth->login($user);
            
            Session::flash('success', 'Inscription réussie ! Bienvenue !');
            return $this->redirect('/');
        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue lors de l\'inscription');
            return $this->view('auth/register', [
                'title' => 'Inscription',
                'old' => [
                    'email' => $email,
                    'firstname' => $firstname,
                    'lastname' => $lastname
                ]
            ]);
        }
    }
    
    /**
     * Déconnexion
     * 
     * CONCEPT : Route protégée par AuthMiddleware (seulement pour les utilisateurs authentifiés)
     */
    #[Route(path: '/logout', methods: ['POST'], name: 'logout', middleware: [new AuthMiddleware()])]
    public function logout(): Response
    {
        $this->auth->logout();
        Session::flash('success', 'Vous avez été déconnecté avec succès');
        return $this->redirect('/');
    }
}
