<?php 
/**
 * 
 * Dashboard
 * 
 */

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Utils;

add_action('admin_menu', 'test_plugin_setup_menu');
function test_plugin_setup_menu(){
    add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}

function test_init() {
    $domain = 
        (get_site_url() != 'https://pgnthemebuild.wpengine.com') 
            ? get_site_url()
            : 'https://pgnthemebuild.wpengine.com';

    echo "<h1>Create new pages</h1>";

    getDataFromJson(PLUGIN_PATH . 'assets/json/redirects.json', $domain);
}

function getDataFromJson( $file, $domain = null ) {
    $url = (isset($domain)) ? $domain : "https://tomasm31.sg-host.com";

    $filePosts = 
        json_decode( 
            file_get_contents( $file, true ) 
        );

    foreach($filePosts as  $post_row => $post ) {
        echo "<h3>Row: $post_row </h3>";

        if( 
            strpos( $post->url, 'visitation' ) !== false ||
            strpos( $post->url, 'send-inmate-money-commissary' ) !== false ||
            strpos( $post->url, 'inmate-phone-calls' ) !== false ||
            strpos( $post->url, 'write-a-prisoner' ) !== false ||
            strpos( $post->url, 'inmate-care-packages' ) !== false
        )
        {
            echo "Estás páginas van o ya han sido creadas!";
        } else {
            $redirectData = $post;
            $dataACF = [
                "if_redirect" => false,
                "pgn_facility_city" => $post->city,
                "pgn_facility_state" => $post->state,
                "pgn_facility_name" => $post->name,
            ];

            $data = [
                "title" => $post->facId,
                "type" => "page",
                "status" => "publish",
                "template" => "jail_detail_2.php",
                "acf" => $dataACF
            ];
            // getWpPost($post->id, $domain );
            $parentPost = createParentWp($data, $domain);
            var_dump($parentPost);

            // // Add posts data
            // $data["parent"] = $parentPost["id"];
            // $data["acf"]["related_write_a_prisoner_url"] = "jail-prisons/" . $parentPost["state_slug"] . "/" . $parentPost["slug"] . "/$post->facId/write-a-prisoner/";
            // $data["acf"]["related_send_money_url"] = "jail-prisons/" . $parentPost["state_slug"] . "/" . $parentPost["slug"] . "/$post->facId/send-inmate-money-commissary/";
            // $data["acf"]["related_corrections_department_url"] = "jail-prisons/" . $parentPost["state_slug"] . "/" . $parentPost["slug"] . "/$post->facId/";
            // $data["acf"]["related_inmate-phone-calls"] = "jail-prisons/" . $parentPost["state_slug"] . "/" . $parentPost["slug"] . "/$post->facId/inmate-phone-calls/";
            // $data["acf"]["related_visitation"] = "jail-prisons/" . $parentPost["state_slug"] . "/" . $parentPost["slug"] . "/$post->facId/visitation/";

            // // Create Post & Redirect
            // $createPost = postWpPost($data, $domain, $redirectData, 'main post');

            // // Create subpages & Redirects
            // postWpSubposts($data, $domain, $createPost, $redirectData);
        }
        echo "<h3>Row ends: $post_row </h3>";        
        break;
    }
}

function createParentWp($data, $domain = null) {
    $parents_id = [
        "alabama" => "988736",
        "alaska" => "989881",
        "andalucía" => "1200785",
        "arecibo" => "1241321",
        "arizona" => "984724",
        "arkansas" => "986177",
        "baden wirttemberg" => "1251619",
        "bayamin" => "1265166",
        "bern" => "1197852",
        "british columbia" => "1213686",
        "ca" => "1195351",
        "california" => "984722",
        "colorado" => "991832",
        "comunidad de madrid" => "1239944",
        "connecticut" => "985537",
        "delaware" => "992370",
        "district of columbia" => "995700",
        "england" => "1209311",
        "estado de mí©xico" => "1264467",
        "florida" => "984725",
        "georgia" => "990650",
        "guayama" => "1224476",
        "hawaii" => "993288",
        "humacao" => "1265149",
        "idaho" => "994591",
        "illinois" => "984726",
        "indiana" => "988938",
        "iowa" => "985887",
        "jayuya" => "1229760",
        "kansas" => "989756",
        "kentucky" => "990783",
        "lisboa" => "1264186",
        "louisiana" => "990873",
        "maine" => "991133",
        "maryland" => "990837",
        "massachusetts" => "989520",
        "mayagí_ez" => "1265116",
        "metro manila" => "1263849",
        "michigan" => "984718",
        "minnesota" => "990840",
        "mississippi" => "991161",
        "missouri" => "990950",
        "montana" => "991555",
        "nebraska" => "984528",
        "nevada" => "991509",
        "new brunswick" => "1241583",
        "new hampshire" => "991395",
        "new jersey" => "985649",
        "new mexico" => "984500",
        "new south wales" => "1239985",
        "new york" => "990906",
        "north carolina" => "990955",
        "north dakota" => "993852",
        "ohio" => "984723",
        "oklahoma" => "991029",
        "oregon" => "984716",
        "pennsylvania" => "984719",
        "ponce" => "1201331",
        "puerto rico" => "1281103",
        "queensland" => "1199389",
        "rhode island" => "990942",
        "rí_o grande" => "1281078",
        "sancti spiritus" => "1259456",
        "scotland" => "1210680",
        "south carolina" => "986330",
        "south dakota" => "991014",
        "st. croix" => "1265223",
        "tennessee" => "990970",
        "texas" => "984721",
        "utah" => "991306",
        "vermont" => "992062",
        "virginia" => "985882",
        "washington" => "984727",
        "west virginia" => "984720",
        "western australia" => "1203484",
        "western visayas" => "1222333",
        "wisconsin" => "984717",
        "wyoming" => "993214"
    ];

    $current_parent = $parents_id[strtolower( $data['acf']['pgn_facility_state'] )];
    $stateSlug = getWpPost($current_parent, $domain, 'parent');

    $parentData = [
        "title" => $data['acf']['pgn_facility_name'],
        "type" => "page",
        "status" => "publish",
        "parent" => $current_parent,
        "template" => "jail_detail_2.php",
        "acf" => [
            "if_redirect" => false,
            "pgn_facility_city" => $data['acf']['pgn_facility_city'],
            "pgn_facility_state" => $data['acf']['pgn_facility_state'],
            "pgn_facility_name" => $data['acf']['pgn_facility_name'],
        ],
    ];

    $parentPost = postWpParent($parentData, $domain);

    return [
        "id" => $parentPost["post_parent_id"],
        "slug" => $parentPost["post_parent_slug"],
        "state_slug" => $stateSlug
    ];
}

// Query
function getWpPost( $post_id, $domain = null, $get_type = null ) {
    $crediantials = 
        (isset($domain)) 
            ? PLUGIN_PATH . 'assets/testing.json'
            : PLUGIN_PATH . 'assets/pigeonly.json';
    $token = 
        json_decode( 
            file_get_contents( $crediantials, true ) 
        )->token;
    $url = (isset($domain)) ? $domain : "https://tomasm31.sg-host.com";

    $client = new Client( ["base_uri" => "$url/wp-json/wp/v2/"] );

    try {
        $response = $client->request("GET", "posts/${post_id}", [
            "headers" => [
                "Accept" => "application/json",
                "Content-type" => "application/json"
            ],
        ]);

        $body = json_decode( $response->getBody()->getContents() );
        // var_dump($body);

    } catch (ClientException $e) {
        $response = $e->getResponse();
        var_dump($responseFromPost);
    }

    if($response->getStatusCode() != 200) {
        echo '<p>Invalid post ID! (Post_type: Post) <br /> Server status: ' . $response->getStatusCode() . '</p>';
        
        try {
            $responseFromPost = $client->request("GET", "pages/${post_id}", [
                "headers" => [
                    "Accept" => "application/json",
                    "Content-type" => "application/json"
                ],
            ]);
            $body = json_decode( $responseFromPost->getBody()->getContents() );
            // var_dump($body);

            echo "<p>Correct post ID! (Post_type: Page) <br /> Server status: " . $responseFromPost->getStatusCode() . '</p>';
        } catch (ClientException $e) {
            $responseFromPost = $e->getResponse();
            var_dump($responseFromPost);

            echo "<p>Invalid post ID too! This post doesn't exist (Post_type: Page) <br /> Server status: " . $responseFromPost->getStatusCode() . '</p>';
        }
    }

    echo '<br />';
    switch ($get_type) {
        case 'parent':
            return $body->slug;
            break;
        
        default:
            break;
    }
}

function postWpParent( $data, $domain = null ) {
    $crediantials = 
        (isset($domain)) 
            ? PLUGIN_PATH . 'assets/testing.json'
            : PLUGIN_PATH . 'assets/pigeonly.json';
    $token = 
        json_decode( 
            file_get_contents( $crediantials, true ) 
        )->token;
    $url = (isset($domain)) ? $domain : "https://tomasm31.sg-host.com";

    $client = new Client( ["base_uri" => "$url/wp-json/wp/v2/"] );
    $post_created;

    try {
        $response = $client->request("POST", "pages/", [
            "headers" => [
                "Authorization" => "Bearer {$token}",
                "Accept" => "application/json",
                "Content-type" => "application/json",
            ],
            'body' => json_encode( $data )
        ]);
        $body = json_decode( $response->getBody()->getContents() );

        // If post status is not "publish"
        if (!$body->status == "publish") {
            putWpPostStatus(
                $body->id,
                $url,
                $token
            );
        }

        $post_created = [
            "post_parent_id" => $body->id,
            "post_parent_slug" => $body->slug,
        ];
        historyCSV($body->title, $body->id, $body->link);
    } catch (ClientException $e) {
        $response = $e->getResponse();
        var_dump($response);
        historyCSV('Error', $body->id, '');
    }

    return $post_created;
}

function postWpPost( $data, $domain = null, $redirectData = [], $post_type = null ) {
    $crediantials = 
        (isset($domain)) 
            ? PLUGIN_PATH . 'assets/testing.json'
            : PLUGIN_PATH . 'assets/pigeonly.json';
    $token = 
        json_decode( 
            file_get_contents( $crediantials, true ) 
        )->token;
    $url = (isset($domain)) ? $domain : "https://tomasm31.sg-host.com";

    $client = new Client( ["base_uri" => "$url/wp-json/wp/v2/"] );

    try {
        $response = $client->request("POST", "pages/", [
            "headers" => [
                "Authorization" => "Bearer {$token}",
                "Accept" => "application/json",
                "Content-type" => "application/json",
            ],
            'body' => json_encode( $data )
        ]);
        $body = json_decode( $response->getBody()->getContents() );

        // If post status is not "publish"
        if ($body->status != "publish") {
            putWpPostStatus(
                $body->id,
                $url,
                $token
            );
        }

        if ($body->status == "publish") {
            $redirect_id;

            switch ($post_type) {        
                case 'visitation':        
                case 'money':
                case 'phone':        
                case 'write':        
                case 'care':
                    $redirect_id = findRelatedId($redirectData, $post_type);
                    break;

                default:
                    $redirect_id = $redirectData->postId;
                    break;
            }

            putWpPostRedirect(
                $redirect_id,
                [
                    "acf" => [
                        "if_redirect" => true,
                        "redirect_to" => $body->link,
                    ]
                ],
                $url,
                $token
            );
        }

        historyCSV($body->title, $body->id, $body->link);
    } catch (ClientException $e) {
        $response = $e->getResponse();
        var_dump($response);
        historyCSV('Error', $body->id, '');
    }

    echo '<br />';
    switch ($post_type) {
        case 'main post':
            echo ($response->getStatusCode() === 200)
                ? '<p>Main post created! Id: ' . $body->id . ' </p>'
                : '<p>Error main post creating! Id: ' . $body->id . ' </p>';
            return $body->id;
            break;

        case 'visitation':
            echo ($response->getStatusCode() === 200)
                ? '<p>- Subpage: Visitation post created! Id: ' . $body->id . ' </p>'
                : '<p>- Subpage: Error visitation post creating! Id: ' . $body->id . ' </p>';
            break;

        case 'money':
            echo ($response->getStatusCode() === 200)
                ? '<p>- Subpage: Money post created! Id: ' . $body->id . ' </p>'
                : '<p>- Subpage: Error money post creating! Id: ' . $body->id . ' </p>';
            break;

        case 'phone':
            echo ($response->getStatusCode() === 200)
                ? '<p>- Subpage: Phone post created! Id: ' . $body->id . ' </p>'
                : '<p>- Subpage: Error phone post creating! Id: ' . $body->id . ' </p>';
            break;

        case 'write':
            echo ($response->getStatusCode() === 200)
                ? '<p>- Subpage: Write post created! Id: ' . $body->id . ' </p>'
                : '<p>- Subpage: Error write post creating! Id: ' . $body->id . ' </p>';
            break;

        case 'care':
            echo ($response->getStatusCode() === 200)
                ? '<p>- Subpage: Care post created! Id: ' . $body->id . ' </p>'
                : '<p>- Subpage: Error care post creating! Id: ' . $body->id . ' </p>';
            break;
        default:
            break;
    }
}


function putWpPostStatus( $post_id, $url, $token ) {
    $data = [ "status" => "publish" ];

    $client = new Client( ["base_uri" => "$url/wp-json/wp/v2/"] );

    $response = $client->request("PUT", "posts/{$post_id}", [
        "headers" => [
            "Authorization" => "Bearer {$token}",
            "Accept" => "application/json",
            "Content-type" => "application/json",
        ],
        'body' => json_encode( $data )
    ]);

    echo '<p>Post status updated! <br /> Server status: ' . $response->getStatusCode() . '</p>';
    return $response->getStatusCode();
}


function putWpPostRedirect( $post_id, $data, $url, $token ) {
    $client = new Client( ["base_uri" => "$url/wp-json/wp/v2/"] );

    $response = $client->request("PUT", "pages/{$post_id}", [
        "headers" => [
            "Authorization" => "Bearer {$token}",
            "Accept" => "application/json",
            "Content-type" => "application/json",
        ],
        'body' => json_encode( $data )
    ]);

    if(!$response->getStatusCode() == 200) {
        echo '<p>Invalid post ID! (Post_type: Page) <br /> Server status: ' . $response->getStatusCode() . '</p>';

        $responseFromPost = $client->request("PUT", "posts/{$post_id}", [
            "headers" => [
                "Authorization" => "Bearer {$token}",
                "Accept" => "application/json",
                "Content-type" => "application/json",
            ],
            'body' => json_encode( $data )
        ]);

        if(!$responseFromPost->getStatusCode() == 200) {
            echo "<p>Invalid post ID too! This post doesn't exist (Post_type: Post) <br /> Server status: " . $responseFromPost->getStatusCode() . '</p>';
        } else {
            echo '<p>Created metadata from redirect! (Post_type: Post) <br /> Server status: ' . $responseFromPost->getStatusCode() . '</p>';
        }
    } else {
        echo '<p>Created metadata from redirect! (Post_type: Page) <br /> Server status: ' . $response->getStatusCode() . '</p>';
    }
}

function postWpSubposts($data, $domain = null, $parent_id, $redirectData) {
    $data["parent"] = $parent_id;

    $data["title"] = 'Visitation';
    postWpPost($data, $domain, $redirectData, 'visitation');

    $data["title"] = 'Send Inmate Money Commissary';
    postWpPost($data, $domain, $redirectData, 'money');

    $data["title"] = 'Inmate Phone Calls';
    postWpPost($data, $domain, $redirectData, 'phone');

    $data["title"] = 'Write A Prisoner';
    postWpPost($data, $domain, $redirectData, 'write');

    $data["title"] = 'Inmate Care Packages';
    postWpPost($data, $domain, $redirectData, 'care');
}

function findRelatedId($data, $searchword) {
    $related_urls_raw = str_replace('[', '', $data->relatedUrls);
    $related_urls_raw_2 = str_replace(']', '', $related_urls_raw);
    $related_id_raw = str_replace('[', '', $data->postIds);
    $related_id_raw_2 = str_replace(']', '', $related_id_raw);
    
    $related_urls = explode(',', $related_urls_raw_2);
    $related_id = explode(',', $related_id_raw_2);
    
    $matches = array_filter( $related_urls, function($var) use ($searchword) {
        return preg_match("/\b$searchword\b/i", $var);
    });
    
    $position;
    foreach ($matches as $key => $value) {
        $position = $key;
        break;
    }
    
    return $related_id[$position];
}

function historyCSV($name, $id, $link) {
    $fichero = $_SERVER['DOCUMENT_ROOT']  . '/wp-content/plugins/redirect-pigeonly/assets/history/conf-v1.csv';
    $actual = file_get_contents($fichero);
    // Añade una nueva persona al fichero
    $actual .= "[$id] $name, $link\n";
    // Escribe el contenido al fichero
    file_put_contents($fichero, $actual);
}