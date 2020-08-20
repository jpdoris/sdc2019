<?php
/**
 * Created by PhpStorm.
 * User: as247
 * Date: 08-May-18
 * Time: 9:32 AM
 */

class Wp2sv_Rest_Check
{
    protected static $instance;
    protected $wp_rewrite;
    protected $request=[];
    function __construct()
    {
        $this->wp_rewrite=new WP_Rewrite();
        $this->addRewriteRules();
        $this->parse_request();
    }

    protected function addRewriteRules(){
        $this->wp_rewrite->add_rule( '^' . rest_get_url_prefix() . '/?$','index.php?rest_route=/','top' );
        $this->wp_rewrite->add_rule( '^' . rest_get_url_prefix() . '/(.*)?','index.php?rest_route=/$matches[1]','top' );
        $this->wp_rewrite->add_rule( '^' . $this->wp_rewrite->index . '/' . rest_get_url_prefix() . '/?$','index.php?rest_route=/','top' );
        $this->wp_rewrite->add_rule( '^' . $this->wp_rewrite->index . '/' . rest_get_url_prefix() . '/(.*)?','index.php?rest_route=/$matches[1]','top' );
    }
    protected function parse_request(){
        $query=null;
        $matches=[];
        $wp_rewrite=$this->wp_rewrite;
        $rewrite = $this->wp_rewrite->wp_rewrite_rules();
        if ( ! empty($rewrite) ) {

            $pathinfo = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '';
            list( $pathinfo ) = explode( '?', $pathinfo );
            $pathinfo = str_replace( "%", "%25", $pathinfo );

            list( $req_uri ) = explode( '?', $_SERVER['REQUEST_URI'] );
            $self = $_SERVER['PHP_SELF'];
            $home_path = trim( parse_url( home_url(), PHP_URL_PATH ), '/' );
            $home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

            // Trim path info from the end and the leading home path from the
            // front. For path info requests, this leaves us with the requesting
            // filename, if any. For 404 requests, this leaves us with the
            // requested permalink.
            $req_uri = str_replace($pathinfo, '', $req_uri);
            $req_uri = trim($req_uri, '/');
            $req_uri = preg_replace( $home_path_regex, '', $req_uri );
            $req_uri = trim($req_uri, '/');
            $pathinfo = trim($pathinfo, '/');
            $pathinfo = preg_replace( $home_path_regex, '', $pathinfo );
            $pathinfo = trim($pathinfo, '/');
            $self = trim($self, '/');
            $self = preg_replace( $home_path_regex, '', $self );
            $self = trim($self, '/');

            // The requested permalink is in $pathinfo for path info requests and
            //  $req_uri for other requests.
            if ( ! empty($pathinfo) && !preg_match('|^.*' . $wp_rewrite->index . '$|', $pathinfo) ) {
                $requested_path = $pathinfo;
            } else {
                // If the request uri is the index, blank it out so that we don't try to match it against a rule.
                if ( $req_uri == $wp_rewrite->index )
                    $req_uri = '';
                $requested_path = $req_uri;
            }
            $requested_file = $req_uri;


            // Look for matches.
            $request_match = $requested_path;
            if ( empty( $request_match ) ) {
                // An empty request could only match against ^$ regex
                if ( isset( $rewrite['$'] ) ) {
                    $matched_rule = '$';
                    $query = $rewrite['$'];
                    $matches = array('');
                }
            } else {
                foreach ( (array) $rewrite as $match => $query ) {
                    // If the requested file is the anchor of the match, prepend it to the path info.
                    if ( ! empty($requested_file) && strpos($match, $requested_file) === 0 && $requested_file != $requested_path )
                        $request_match = $requested_file . '/' . $requested_path;

                    if ( preg_match("#^$match#", $request_match, $matches) ||
                        preg_match("#^$match#", urldecode($request_match), $matches) ) {

                        if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
                            // This is a verbose page match, let's check to be sure about it.
                            $page = get_page_by_path( $matches[ $varmatch[1] ] );
                            if ( ! $page ) {
                                continue;
                            }

                            $post_status_obj = get_post_status_object( $page->post_status );
                            if ( ! $post_status_obj->public && ! $post_status_obj->protected
                                && ! $post_status_obj->private && $post_status_obj->exclude_from_search ) {
                                continue;
                            }
                        }

                        // Got a match.
                        $matched_rule = $match;
                        break;
                    }
                }
            }

            if ( isset( $matched_rule ) ) {
                // Trim the query of everything up to the '?'.
                $query = preg_replace("!^.+\?!", '', $query);

                // Substitute the substring matches into the query.
                $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));


                // Parse the query.
                parse_str($query, $perma_query_vars);

            }

            // If req_uri is empty or if it is a request for ourself, unset error.
            if ( empty($requested_path) || $requested_file == $self || strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false ) {
                if ( isset($perma_query_vars) && strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false )
                    unset( $perma_query_vars );
            }
        }
        if(isset($perma_query_vars)){
            $this->request=$perma_query_vars;
        }
        $this->request=array_merge($this->request,$_GET,$_POST);
    }
    function isRest(){
        return !empty($this->request['rest_route']);
    }
    static function check(){
        return static::instance()->isRest();
    }

    /**
     * @return static
     */
    static function instance(){
        if(!static::$instance){
            static::$instance=new static();
        }
        return static::$instance;
    }
}