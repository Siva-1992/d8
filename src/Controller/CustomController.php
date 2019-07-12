<?php

/**
 * @file CustomController.php
 * @author Siva Iyyappan
 *
 */

namespace Drupal\custom_behaviors\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\NodeInterface;

/**
 * Provides route responses for the Test module.
 */
class CustomController {
  /**
    * Returns a simple json page with node details.
    *
    * @return array
    *   A simple renderable array with node details.
    */
  public function pagejson($api_key, $node1) {
    // Get the API Key from database variable
    $db_api_key = \Drupal::state()->get('siteapikey');
    // Check the API Key with parameter
    if($db_api_key == $api_key){
      // Load the node details
      $node = Node::load($node1);
      if(!empty($node)){
        $node_type = $node->type->entity->label();
        if($node_type == 'Basic page'){
          $response = new JsonResponse();
          $config = \Drupal::config('system.site');
          $data = array(
            'date' => time(),
            'site_name' => $config->get('name'),
            'site_email' => $config->get('mail'),
            'random_node' => array(
              'title' => $node->get('title')->getValue()[0]['value'],
              'body' => $node->get('body')->getValue()[0]['value'],
            )  
          );
          $response->setData($data);
          // Return the Json response
          return $response;
        }
        // Redirect the access denied page if it's having error
        else{
          throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
        }
      }
      else{
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(); 
      }
    }
    else{
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
    }
  }
}
