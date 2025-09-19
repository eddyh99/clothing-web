<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Exceptions\TokenExpiredException;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['curl','access'];
    protected string $cspNonce;
    protected $session;
    protected $validation;
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        set_exception_handler([$this, 'handleException']);

        $this->cspNonce = bin2hex(random_bytes(16));
        service('renderer')->setVar('nonce', $this->cspNonce);

        // Content Security Policy: allow specific CDNs for scripts/styles, block mixed content, upgrade insecure, report violations
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$this->cspNonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.datatables.net https://code.jquery.com",
            "style-src 'self' 'nonce-{$this->cspNonce}' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://cdn.datatables.net https://code.jquery.com",
            "img-src 'self' data:",
            "font-src 'self' https://fonts.gstatic.com",
            "connect-src 'self' https://api-valura.softwarebisnisku.com",
            "frame-src 'none'",
            "block-all-mixed-content",
            "upgrade-insecure-requests",
            "report-to /csp-report-endpoint"
        ];
        $response->setHeader('Content-Security-Policy', implode('; ', $cspDirectives));
        $response->setHeader('Content-Security-Policy-Report-Only',implode('; ', $cspDirectives));
        // Preload any models, libraries, etc, here.

        $this->session = service('session');
        $this->validation = service('validation');
    }

   public function handleException($exception)
    {
        if ($exception instanceof TokenExpiredException) {
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                // For AJAX requests, return JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Session expired',
                    'redirect' => base_url('/logout')
                ]);
                exit;
            } else {
                // For normal requests, redirect
                return redirect()->to('/logout');
            }
        }
        
        // Handle other exceptions if needed
        throw $exception;
    }
}
