<?php
/**
 * Provides examples of hooking the filters provided by the [mla_gallery] shortcode:
 *
 *  - In the "mla_gallery_query_arguments" filter are two examples of custom SQL queries
 *    that replace the usual get_posts/WP_Query results.
 *
 *  - In the "mla_gallery_item_values" filter are six examples that modify the 
 *    attachment-specific data elements used to compose the gallery display.
 *
 *  - The "mla_gallery_raw_attributes", "mla_gallery_open_template" and
 *    "mla_gallery_item_values" filters contain an example that adds a "file delete"
 *    link to the gallery items.
 *
 * The example plugin documents ALL the filters available in the [mla_gallery] shortcode
 * and illustrates some of the techniques you can use to customize the gallery display.
 *
 * @package MLA Gallery Hooks Example
 * @version 1.08
 */

/*
Plugin Name: MLA Gallery Hooks Example
Plugin URI: http://fairtradejudaica.org/media-library-assistant-a-wordpress-plugin/
Description: Provides examples of hooking the filters provided by the [mla_gallery] shortcode
Author: David Lingren
Version: 1.08
Author URI: http://fairtradejudaica.org/our-story/staff/

Copyright 2013 - 2016 David Lingren

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You can get a copy of the GNU General Public License by writing to the
	Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/

/**
 * Class MLA Gallery Hooks Example hooks all of the filters provided by the [mla_gallery] shortcode
 *
 * Call it anything you want, but give it an unlikely and hopefully unique name. Hiding everything
 * else inside a class means this is the only name you have to worry about.
 *
 * @package MLA Gallery Hooks Example
 * @since 1.00
 */
class MLAGalleryHooksExample {
	/**
	 * Initialization function, similar to __construct()
	 *
	 * @since 1.00
	 *
	 * @return	void
	 */
	public static function initialize() {
		/*
		 * The filters are only useful for front-end posts/pages; exit if in the admin section
		 */
		if ( is_admin() )
			return;

		/*
		 * add_filter parameters:
		 * $tag - name of the hook you're filtering; defined by [mla_gallery]
		 * $function_to_add - function to be called when [mla_gallery] applies the filter
		 * $priority - default 10; lower runs earlier, higher runs later
		 * $accepted_args - number of arguments your function accepts
		 *
		 * Comment out the filters you don't need; save them for future use
		 */
		add_filter( 'mla_gallery_raw_attributes', 'MLAGalleryHooksExample::mla_gallery_raw_attributes_filter', 10, 1 );
		add_filter( 'mla_gallery_attributes', 'MLAGalleryHooksExample::mla_gallery_attributes_filter', 10, 1 );
		add_filter( 'mla_gallery_initial_content', 'MLAGalleryHooksExample::mla_gallery_initial_content_filter', 10, 2 );
		add_filter( 'mla_gallery_arguments', 'MLAGalleryHooksExample::mla_gallery_arguments_filter', 10, 1 );
		add_filter( 'mla_gallery_query_attributes', 'MLAGalleryHooksExample::mla_gallery_query_attributes_filter', 10, 1 );
		add_filter( 'mla_gallery_query_arguments', 'MLAGalleryHooksExample::mla_gallery_query_arguments_filter', 10, 1 );
		add_action( 'mla_gallery_wp_query_object', 'MLAGalleryHooksExample::mla_gallery_wp_query_object_action', 10, 1 );
		add_filter( 'mla_gallery_final_content', 'MLAGalleryHooksExample::mla_gallery_final_content_filter', 10, 1 );

		add_filter( 'use_mla_gallery_style', 'MLAGalleryHooksExample::use_mla_gallery_style_filter', 10, 2 );

		add_filter( 'mla_gallery_style_values', 'MLAGalleryHooksExample::mla_gallery_style_values_filter', 10, 1 );
		add_filter( 'mla_gallery_style_template', 'MLAGalleryHooksExample::mla_gallery_style_template_filter', 10, 1 );
		add_filter( 'mla_gallery_style_parse', 'MLAGalleryHooksExample::mla_gallery_style_parse_filter', 10, 3 );

		add_filter( 'mla_gallery_open_values', 'MLAGalleryHooksExample::mla_gallery_open_values_filter', 10, 1 );
		add_filter( 'mla_gallery_open_template', 'MLAGalleryHooksExample::mla_gallery_open_template_filter', 10, 1 );
		add_filter( 'mla_gallery_open_parse', 'MLAGalleryHooksExample::mla_gallery_open_parse_filter', 10, 3 );

		add_filter( 'mla_gallery_style', 'MLAGalleryHooksExample::mla_gallery_style_filter', 10, 5 );

		add_filter( 'mla_gallery_row_open_values', 'MLAGalleryHooksExample::mla_gallery_row_open_values_filter', 10, 1 );
		add_filter( 'mla_gallery_row_open_template', 'MLAGalleryHooksExample::mla_gallery_row_open_template_filter', 10, 1 );
		add_filter( 'mla_gallery_row_open_parse', 'MLAGalleryHooksExample::mla_gallery_row_open_parse_filter', 10, 3 );

		add_filter( 'mla_gallery_item_values', 'MLAGalleryHooksExample::mla_gallery_item_values_filter', 10, 1 );
		add_filter( 'mla_gallery_item_template', 'MLAGalleryHooksExample::mla_gallery_item_template_filter', 10, 1 );
		add_filter( 'mla_gallery_item_parse', 'MLAGalleryHooksExample::mla_gallery_item_parse_filter', 10, 3 );

		add_filter( 'mla_gallery_row_close_values', 'MLAGalleryHooksExample::mla_gallery_row_close_values_filter', 10, 1 );
		add_filter( 'mla_gallery_row_close_template', 'MLAGalleryHooksExample::mla_gallery_row_close_template_filter', 10, 1 );
		add_filter( 'mla_gallery_row_close_parse', 'MLAGalleryHooksExample::mla_gallery_row_close_parse_filter', 10, 3 );

		add_filter( 'mla_gallery_close_values', 'MLAGalleryHooksExample::mla_gallery_close_values_filter', 10, 1 );
		add_filter( 'mla_gallery_close_template', 'MLAGalleryHooksExample::mla_gallery_close_template_filter', 10, 1 );
		add_filter( 'mla_gallery_close_parse', 'MLAGalleryHooksExample::mla_gallery_close_parse_filter', 10, 3 );
	}

	/**
	 * Save the shortcode attributes
	 *
	 * @since 1.00
	 *
	 * @var	array
	 */
	private static $shortcode_attributes = array();

	/**
	 * MLA Gallery Raw (Display) Attributes
	 *
	 * This filter gives you an opportunity to record or modify the arguments passed in to the shortcode
	 * before they pass through the logic to handle the 'mla_page_parameter' and "request:" prefix processing.
	 *
	 * The $shortcode_attributes array is where you will find any of your own parameters that are coded in the
	 * shortcode, e.g., [mla_gallery my_parameter="my value"].
	 *
	 * @since 1.03
	 *
	 * @param	array	the raw shortcode parameters passed in to the shortcode
	 *
	 * @return	array	updated shortcode attributes
	 */
	public static function mla_gallery_raw_attributes_filter( $shortcode_attributes ) {
		/*
		 * Uncomment the error_log statements in any of the filters to see what's passed in
		 */
		//error_log( 'MLAGalleryHooksExample::mla_gallery_raw_attributes_filter $shortcode_attributes = ' . var_export( $shortcode_attributes, true ), 0 );

		/*
		 * Note that the global $post; object is available here and in all later filters.
		 * It contains the post/page on which the [mla_gallery] appears.
		 * Some [mla_gallery] invocations are not associated with a post/page; these will
		 * have a substitute $post object with $post->ID == 0.
		 */
		global $post;
		//error_log( 'MLAGalleryHooksExample::mla_gallery_raw_attributes_filter $post->ID = ' . var_export( $post->ID, true ), 0 );

		/*
		 * For this example, we delete the selected file.
		 */
		if ( isset( $shortcode_attributes['my_filter'] ) && 'allow file deletion' == $shortcode_attributes['my_filter'] ) {
			if ( isset( $_REQUEST['attachment_ID'] ) ) {
				$id = (integer) $_REQUEST['attachment_ID'];
				if ( current_user_can( 'delete_post', $id ) ) { 
					$result = wp_delete_attachment( $id );
				} else {
					$result = false;
				}

				if ( ( false === $result ) || ( NULL === $result ) ) {
					$shortcode_attributes['gallery_open_message'] = "Could not delete attachment_ID '{$id}'.";
				} else {
					$result = (array) $result; // Some wp_delete_attachment calls return an object
					$shortcode_attributes['gallery_open_message'] = "Attachment '{$result['post_title']}' (ID {$id}) has been deleted.";
				}

				unset( $_REQUEST['attachment_ID'] );
			}
		}

		return $shortcode_attributes;
	} // mla_gallery_raw_attributes_filter

	/**
	 * MLA Gallery (Display) Attributes
	 *
	 * This filter gives you an opportunity to record or modify the arguments passed in to the shortcode
	 * before they are merged with the default arguments used for the gallery display.
	 *
	 * The $shortcode_attributes array is where you will find any of your own parameters that are coded in the
	 * shortcode, e.g., [mla_gallery my_parameter="my value"].
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters passed in to the shortcode
	 *
	 * @return	array	updated shortcode attributes
	 */
	public static function mla_gallery_attributes_filter( $shortcode_attributes ) {
		/*
		 * Uncomment the error_log statements in any of the filters to see what's passed in
		 */
		//error_log( 'MLAGalleryHooksExample::mla_gallery_attributes_filter $shortcode_attributes = ' . var_export( $shortcode_attributes, true ), 0 );

		/*
		 * Save the attributes for use in the later filters
		 */
		self::$shortcode_attributes = $shortcode_attributes;

		return $shortcode_attributes;
	} // mla_gallery_attributes_filter

	/**
	 * Save the enclosed content
	 *
	 * @since 1.02
	 *
	 * @var	NULL|string
	 */
	private static $shortcode_content = NULL;

	/**
	 * MLA Gallery Enclosed Content, initial filter
	 *
	 * This filter gives you an opportunity to record or modify the content enclosed by the shortcode
	 * when the [mla_gallery]content[/mla_gallery] form is used.
	 * This initial filter is called just after the 'mla_gallery_attributes' filter above.
	 *
	 * @since 1.02
	 *
	 * @param	NULL|string	content enclosed by the shortcode, if any
	 * @param	array	the shortcode parameters passed in to the shortcode
	 *
	 * @return	array	updated shortcode content
	 */
	public static function mla_gallery_initial_content_filter( $shortcode_content, $shortcode_attributes ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_initial_content_filter $shortcode_content = ' . var_export( $shortcode_content, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_initial_content_filter $shortcode_attributes = ' . var_export( $shortcode_attributes, true ), 0 );

		/*
		 * Save the attributes for use in the later filters
		 */
		self::$shortcode_content = $shortcode_content;

		return $shortcode_content;
	} // mla_gallery_initial_content_filter

	/**
	 * Save the shortcode arguments
	 *
	 * @since 1.00
	 *
	 * @var	array
	 */
	private static $all_display_parameters = array();

	/**
	 * MLA Gallery (Display) Arguments
	 *
	 * This filter gives you an opportunity to record or modify the gallery display arguments
	 * after the shortcode attributes are merged with the default arguments.
	 *
	 * Note that the values in this array are input or default values, not the final computed values
	 * used for the gallery display.  The computed values are in the $style_values, $markup_values and
	 * $item_values arrays passed to later filters below.
	 *
	 * @since 1.00
	 *
	 * @param	array	shortcode arguments merged with gallery display defaults, so every possible parameter is present
	 *
	 * @return	array	updated gallery display arguments
	 */
	public static function mla_gallery_arguments_filter( $all_display_parameters ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_arguments_filter $all_display_parameters = ' . var_export( $all_display_parameters, true ), 0 );

		self::$all_display_parameters = $all_display_parameters;
		return $all_display_parameters;
	} // mla_gallery_arguments_filter

	/**
	 * Save the query attributes
	 *
	 * @since 1.00
	 *
	 * @var	array
	 */
	private static $query_attributes = array();

	/**
	 * MLA Gallery Query Attributes
	 *
	 * This filter gives you an opportunity to record or modify the arguments passed in to the shortcode
	 * before they are merged with the default arguments used to select the attachments for the gallery.
	 *
	 * The query attributes passed in to this filter are the same as those passed through the
	 * "MLA Gallery (Display) Attributes" filter above. This filter is provided so you can modify
	 * the data selection attributes without disturbing the attributes used for gallery display.
	 *
	 * @since 1.00
	 *
	 * @param	array	the shortcode parameters passed in to the shortcode
	 *
	 * @return	array	updated shortcode attributes
	 */
	public static function mla_gallery_query_attributes_filter( $query_attributes ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_query_attributes_filter $query_attributes = ' . var_export( $query_attributes, true ), 0 );

		self::$query_attributes = $query_attributes;
		return $query_attributes;
	} // mla_gallery_query_attributes_filter

	/**
	 * Save the query arguments
	 *
	 * @since 1.00
	 *
	 * @var	array
	 */
	private static $all_query_parameters = array();

	/**
	 * MLA Gallery Query Arguments
	 *
	 * This filter gives you an opportunity to record or modify the attachment query arguments
	 * after the shortcode attributes are merged with the default arguments.
	 *
	 * @since 1.00
	 *
	 * @param	array	shortcode arguments merged with attachment selection defaults, so every possible parameter is present
	 *
	 * @return	array	updated attachment query arguments
	 */
	public static function mla_gallery_query_arguments_filter( $all_query_parameters ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_query_arguments_filter $all_query_parameters = ' . var_export( $all_query_parameters, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_query_arguments_filter self::$shortcode_attributes = ' . var_export( self::$shortcode_attributes, true ), 0 );

		self::$all_query_parameters = $all_query_parameters;

		/*
		 * This example executes a custom SQL query that cannot be done with the usual
		 * WordPress WP_Query arguments. The query results are fed back to the [mla_gallery]
		 * shortcode as a list of attachments using the "include" parameter.
		 *
		 * The query supported in this example's "recent_random_uploads" parameter selects one
		 * random image from the "most recent" uploads. The number of uploads considered "most recent"
		 * is taken from the value of the shortcode parameter, e.g., "most_recent_uploads=10" to
		 * select ten recent uploads.
		 *
		 * You can also display more than one image by adding a second value to the parameter,
		 * e.g., "most_recent_uploads=10,2" to display 2 of the ten most recent uploads.
		 *
		 * We use a shortcode parameter of our own to apply this filter on a gallery-by-gallery
		 * basis, leaving other [mla_gallery] instances untouched. If the "recent_random_uploads"
		 * parameter is not present, we have nothing to do. If the parameter IS present, build a
		 * custom query that first selects the recent uploads and then picks one of them at random.
		 */		
		if ( isset( self::$shortcode_attributes['recent_random_uploads'] ) ) {
			global $wpdb;

			// Extract the number of "recent posts" to consider and the (optional) number to display
			$limits = explode( ',', self::$shortcode_attributes['recent_random_uploads'] );
			$recent_limit = absint( $limits[0] );
			if ( 0 == $recent_limit ) {
				return $all_query_parameters;
			}
			
			$display_limit = isset( $limits[1] ) ? absint( $limits[1] ) : 1;
			if ( 0 == $display_limit ) {
				$display_limit = 1;
			}

			// Build an array of SQL clauses
			$query = array();
			$query_parameters = array();

			$query[] = "SELECT p.ID FROM (";
			$query[] = "SELECT ID FROM {$wpdb->posts} WHERE (";
			$query[] = "( post_type = 'attachment' )";
			$query[] = "AND ( post_status = 'inherit' )";
			$query[] = "AND ( post_parent > 0 )";
			$query[] = "AND ( post_mime_type LIKE 'image/%%' )";
			$query[] = ") ORDER BY post_date DESC";

			$query[] = "LIMIT %d";
			$query_parameters[] = $recent_limit;

			$query[] = ") AS p ORDER BY RAND()";

			$query[] = "LIMIT %d";
			$query_parameters[] = $display_limit;

			$query =  join(' ', $query);
			$ids = $wpdb->get_results( $wpdb->prepare( $query, $query_parameters ) );
			if ( is_array( $ids ) ) {
				$includes = array();
				foreach ( $ids as $id ) {
					$includes[] = $id->ID;
				}
				$all_query_parameters['include'] = implode( ',', $includes );
			} else {
				$all_query_parameters['include'] = '1'; // return no images
			}

			// Remove redundant parameters from the final query
			$all_query_parameters['post_mime_type'] = 'all';
			$all_query_parameters['orderby'] = 'none';
			$all_query_parameters['post_status'] = 'all'; 

			return $all_query_parameters;
		} // parameter "recent_random_uploads" is present

		/*
		 * This example executes a custom SQL query that cannot be done with the usual
		 * WordPress WP_Query arguments. The query results are fed back to the [mla_gallery]
		 * shortcode as a list of attachments using the "include" parameter.
		 *
		 * The queries supported in this example's "my_custom_sql" parameter include:
		 *
		 * - one or more taxonomy term lists, with include_children
		 * - one or more post_parent values
		 * - one or more post_type values
		 *
		 * The three query parameters are joined with "OR", so items matching any of the three
		 * parameters will be included. Any combination of the three parameters is accepted.
		 *
		 * We use a shortcode parameter of our own to apply this filter on a gallery-by-gallery
		 * basis, leaving other [mla_gallery] instances untouched. If the "my_custom_sql" parameter
		 * is not present, we have nothing to do. If the parameter IS present, extract taxonomy, 
		 * post_parent and parent_type values, then build a custom query that connects them with "OR"
		 * (WordPress would use "AND").
		 */		
		if ( isset( self::$shortcode_attributes['my_custom_sql'] ) ) {
			global $wpdb;

			// Make sure $my_query_vars is an array, even if it's empty
			$my_query_vars = self::$shortcode_attributes['my_custom_sql'];
			if ( empty( $my_query_vars ) ) {
				$my_query_vars = array();
			} elseif ( is_string( $my_query_vars ) ) {
				$my_query_vars = shortcode_parse_atts( $my_query_vars );
			}

			// Start with empty parameter values
			$ttids = array();
			$post_parents = array();
			$parent_types = array();

			// Find taxonomy argument, if present, and collect terms
			$taxonomies = get_taxonomies( array( 'object_type' => array( 'attachment' ) ), 'names' );
			foreach( $taxonomies as $taxonomy ) {
				if ( empty( $my_query_vars[ $taxonomy ] ) ) {
					continue;
				}

				// Found the taxonomy; collect the terms
				$include_children =  isset( $my_query_vars['include_children'] ) && 'true' == strtolower( trim( $my_query_vars['include_children'] ) );

				// Allow for multiple term slug values
				$terms = array();
				$slugs = explode( ',', $my_query_vars[ $taxonomy ] );
				foreach ( $slugs as $slug ) {
					$args = array( 'slug' => $slug, 'hide_empty' => false );
					$terms = array_merge( $terms, get_terms( $taxonomy, $args ) );
				}

				foreach( $terms as $term ) {
					// Index by ttid to remove duplicates
					$ttids[ $term->term_taxonomy_id ] = $term->term_taxonomy_id;

					if ( $include_children ) {
						$args = array( 'child_of' => $term->term_id, 'hide_empty' => false );
						$children = get_terms( 'attachment_category', $args );
						foreach( $children as $child ) {
							$ttids[] = $child->term_taxonomy_id;
						}
					} // include_children
				} // $term

				break;
			}

			if ( isset( $my_query_vars['post_parent'] ) ) {
				// Allow for multiple parent values
				$post_parents = explode( ',', $my_query_vars['post_parent'] );
			}

			if ( isset( $my_query_vars['parent_type'] ) ) {
				// Allow for multiple parent values
				$parent_types = explode( ',', $my_query_vars['parent_type'] );
			}

			// Build an array of SQL clauses
			$query = array();
			$query_parameters = array();

			$query[] = "SELECT p.ID FROM {$wpdb->posts} AS p";

			if ( ! empty( $parent_types ) ) {
				$query[] = "LEFT JOIN {$wpdb->posts} as p2";
				$query[] = "ON (p.post_parent = p2.ID)";
			}

			if ( ! empty( $ttids ) ) {
				$query[] = "LEFT JOIN {$wpdb->term_relationships} as tr";
				$query[] = "ON (p.ID = tr.object_id)";
			}

			// Start with a WHERE clause that doesn't match anything, since OR is the connector				
			$query[] = 'WHERE ( ( 1=0 )';

			if ( ! empty( $post_parents ) ) {
				$placeholders = array();
				foreach ( $post_parents as $post_parent ) {
					$placeholders[] = '%s';
					$query_parameters[] = $post_parent;
				}

				$query[] = 'OR ( p.post_parent IN (' . join( ',', $placeholders ) . ') )';
			}

			if ( ! empty( $parent_types ) ) {
				$placeholders = array();
				foreach ( $parent_types as $parent_type ) {
					$placeholders[] = '%s';
					$query_parameters[] = $parent_type;
				}

				$query[] = 'OR ( p2.post_type IN (' . join( ',', $placeholders ) . ') )';
			}

			if ( ! empty( $ttids ) ) {
				$placeholders = array();
				foreach ( $ttids as $ttid ) {
					$placeholders[] = '%s';
					$query_parameters[] = $ttid;
				}

				$query[] = 'OR ( tr.term_taxonomy_id IN (' . join( ',', $placeholders ) . ') )';
			}

			// Close the WHERE clause
			$query[] = ')';

			$query[] = "AND (p.post_mime_type LIKE 'image/%%')";
			$query[] = "AND p.post_type = 'attachment'";
			$query[] = "AND p.post_status = 'inherit'";
			$query[] = "GROUP BY p.ID";
			// ORDER BY clause would go here, if needed

			/*
			 * Add pagination to our query, then remove it from the query
			 * that WordPress will process after we're done.
			 */
			$paged = $all_query_parameters['paged'];
			if ( empty( $paged ) ) {
				$paged = 1;
			} elseif ( 'current' == strtolower( $paged ) ) {
				/*
				 * Note: The query variable 'page' holds the pagenumber for a single paginated
				 * Post or Page that includes the <!--nextpage--> Quicktag in the post content. 
				 */
				if ( get_query_var( 'page' ) ) {
					$paged = get_query_var( 'page' );
				} else {
					$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
				}
			} elseif ( is_numeric( $paged ) ) {
				$paged = absint( $paged );
			} elseif ( '' === $paged ) {
				$paged = 1;
			}

			$limit = absint( ! empty( $all_query_parameters['posts_per_page'] ) ? $all_query_parameters['posts_per_page'] : $all_query_parameters['numberposts'] );
			$offset = $limit * ( $paged - 1);
			if ( 0 < $offset && 0 < $limit ) {
				$query[] = 'LIMIT %d, %d';
				$query_parameters[] = $offset;
				$query_parameters[] = $limit;
			} elseif ( 0 < $limit ) {
				$query[] = 'LIMIT %d';
				$query_parameters[] = $limit;
			} elseif ( 0 < $offset ) {
				$query[] = 'LIMIT %d, %d';
				$query_parameters[] = $offset;
				$query_parameters[] = 0x7FFFFFFF; // big number!
			}

			$all_query_parameters['paged'] = NULL;
			$all_query_parameters['posts_per_page'] = 0;
			$all_query_parameters['numberposts'] = 0;

			$query =  join(' ', $query);
			$ids = $wpdb->get_results( $wpdb->prepare( $query, $query_parameters ) );
			if ( is_array( $ids ) ) {
				$includes = array();
				foreach ( $ids as $id ) {
					$includes[] = $id->ID;
				}
				$all_query_parameters['include'] = implode( ',', $includes );
			} else {
				$all_query_parameters['include'] = '1'; // return no images
			}

			return $all_query_parameters;
		} // parameter "my_custom_sql" is present

		return $all_query_parameters;
	} // mla_gallery_query_arguments_filter

	/**
	 * Save some of the WP_Query object properties
	 *
	 * @since 1.00
	 *
	 * @var	array
	 */
	private static $wp_query_properties = array();

	/**
	 * MLA Gallery WP Query Object
	 *
	 * This action gives you an opportunity (read-only) to record anything you need from the WP_Query object used
	 * to select the attachments for gallery display. This is the ONLY point at which the WP_Query object is defined.
	 *
	 * @since 1.00
	 * @uses MLAShortcodes::$mla_gallery_wp_query_object
	 *
	 * @param	array	query arguments passed to WP_Query->query
	 *
	 * @return	void	actions never return anything
	 */
	public static function mla_gallery_wp_query_object_action( $query_arguments ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_wp_query_object_action $query_arguments = ' . var_export( $query_arguments, true ), 0 );

		self::$wp_query_properties = array();
		self::$wp_query_properties ['request'] = MLAShortcodes::$mla_gallery_wp_query_object->request;
		self::$wp_query_properties ['query_vars'] = MLAShortcodes::$mla_gallery_wp_query_object->query_vars;
		self::$wp_query_properties ['post_count'] = MLAShortcodes::$mla_gallery_wp_query_object->post_count;

		//error_log( 'MLAGalleryHooksExample::mla_gallery_wp_query_object_action self::$wp_query_properties = ' . var_export( self::$wp_query_properties, true ), 0 );

		/*
		 * Unlike Filters, Actions never return anything
		 */
		return;
	} // mla_gallery_wp_query_object_action

	/**
	 * MLA Gallery Enclosed Content, final filter
	 *
	 * This filter gives you an opportunity to record or modify the content enclosed by the shortcode
	 * when the [mla_gallery]content[/mla_gallery] form is used.
	 * This final filter is called just after the WP_query and before control is passed
	 * to the alternate gallery shortcode.
	 *
	 * @since 1.02
	 *
	 * @param	NULL|string	content enclosed by the shortcode, if any
	 *
	 * @return	array	updated shortcode content
	 */
	public static function mla_gallery_final_content_filter( $shortcode_content ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_final_content_filter $shortcode_content = ' . var_export( $shortcode_content, true ), 0 );

		return $shortcode_content;
	} // mla_gallery_final_content_filter

	/**
	 * Use MLA Gallery Style
	 *
	 * You can use this filter to allow or suppress the inclusion of CSS styles in the
	 * gallery output. Return 'true' to allow the styles, false to suppress them. You can also
	 * suppress styles by returning an empty string from the mla_gallery_style_parse_filter below.
	 *
	 * @since 1.00
	 *
	 * @param	boolean	true unless the mla_style parameter is "none"
	 * @param	string	value of the mla_style parameter
	 *
	 * @return	boolean	true to fetch and parse the style template, false to leave it empty
	 */
	public static function use_mla_gallery_style_filter( $use_style_template, $style_template_name ) {
		//error_log( 'MLAGalleryHooksExample::use_mla_gallery_style_filter $use_style_template = ' . var_export( $use_style_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::use_mla_gallery_style_filter $style_template_name = ' . var_export( $style_template_name, true ), 0 );

		/*
		 * Filters must return the first argument passed in, unchanged or updated
		 */
		return $use_style_template;
	} // use_mla_gallery_style_filter

	/**
	 * MLA Gallery Style Values
	 *
	 * The "Values" series of filters gives you a chance to modify the substitution parameter values
	 * before they are used to complete the associated template (in the corresponding "Parse" filter).
	 * It is called just before the values are used to parse the associated template.
	 * You can add, change or delete parameters as needed.
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_style_values_filter( $style_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_values_filter $style_values = ' . var_export( $style_values, true ), 0 );

		/*
		 * You also have access to the PHP Super Globals, e.g., $_REQUEST, $_SERVER
		 */
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_values_filter $_REQUEST = ' . var_export( $_REQUEST, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_values_filter $_SERVER[ REQUEST_URI ] = ' . var_export( $_SERVER['REQUEST_URI'], true ), 0 );

		/*
		 * You can use the WordPress globals like $wp_query, $wpdb and $table_prefix as well.
		 * Note that $wp_query contains values for the post/page query, NOT the [mla_gallery] query.
		 */
		global $wp_query;
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_values_filter $wp_query->query = ' . var_export( $wp_query->query, true ), 0 );

		return $style_values;
	} // mla_gallery_style_values_filter

	/**
	 * MLA Gallery Style Template
	 *
	 * The "Template" series of filters gives you a chance to modify the template value before
	 * it is used to generate the HTML markup (in the corresponding "Parse" filter).
	 * It is called just before the template is used to generate the markup.
	 * You can modify the template as needed.
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_style_template_filter( $style_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_template_filter $style_template = ' . var_export( $style_template, true ), 0 );

		return $style_template;
	} // mla_gallery_style_template_filter

	/**
	 * MLA Gallery Style Parse
	 *
	 * The "Parse" series of filters gives you a chance to modify or replace the HTML markup
	 * that will be added to the [mla_gallery] output. It is called just after the values array
	 * (updated in the corresponding "Values" filter) is combined (parsed) with the template.
	 * You can modify the HTML markup already prepared or start over with the template and the
	 * substitution values.
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_style_parse_filter( $html_markup, $style_template, $style_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_parse_filter $style_template = ' . var_export( $style_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_parse_filter $style_values = ' . var_export( $style_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_style_parse_filter

	/**
	 * MLA Gallery Open Values
	 *
	 * Note: The $markup_values array is shared among the open, row open, row close and close functions.
	 * It is also used to initialize the $item_values array.
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_open_values_filter( $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_open_values_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $markup_values;
	} // mla_gallery_open_values_filter

	/**
	 * MLA Gallery Open Template
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_open_template_filter( $open_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_open_template_filter $open_template = ' . var_export( $open_template, true ), 0 );

		/*
		 * Check for a display message
		 */
		if ( isset( self::$shortcode_attributes['gallery_open_message'] ) ) {
			$open_template = '<p><strong>' . self::$shortcode_attributes['gallery_open_message'] . '</strong></p>' . $open_template;
		}

		return $open_template;
	} // mla_gallery_open_template_filter

	/**
	 * MLA Gallery Open Parse
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_open_parse_filter( $html_markup, $open_template, $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_open_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_open_parse_filter $open_template = ' . var_export( $open_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_open_parse_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_open_parse_filter

	/**
	 * MLA Gallery Style
	 *
	 * This is an old filter retained for compatibility with earlier MLA versions.
	 * You will probably find the "Values" and "Parse" filters more useful.
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup for "gallery style" and "gallery open", combined
	 * @param	array	parameter_name => parameter_value pairs for gallery style
	 * @param	array	parameter_name => parameter_value pairs for gallery open
	 * @param	string	template used to generate the HTML markup for gallery style
	 * @param	string	template used to generate the HTML markup for gallery open
	 *
	 * @return	array	updated HTML markup for "gallery style" and "gallery open" output
	 */
	public static function mla_gallery_style_filter( $html_markup, $style_values, $open_values, $style_template, $open_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_filter $style_values = ' . var_export( $style_values, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_filter $open_values = ' . var_export( $open_values, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_filter $style_template = ' . var_export( $style_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_style_filter $open_template = ' . var_export( $open_template, true ), 0 );

		return $html_markup;
	} // mla_gallery_style_filter

	/**
	 * MLA Gallery Row Open Values
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_row_open_values_filter( $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_open_values_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $markup_values;
	} // mla_gallery_row_open_values_filter

	/**
	 * MLA Gallery Row Open Template
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_row_open_template_filter( $row_open_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_open_template_filter $row_open_template = ' . var_export( $row_open_template, true ), 0 );

		return $row_open_template;
	} // mla_gallery_row_open_template_filter

	/**
	 * MLA Gallery Row Open Parse
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_row_open_parse_filter( $html_markup, $row_open_template, $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_open_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_open_parse_filter $row_open_template = ' . var_export( $row_open_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_open_parse_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_row_open_parse_filter

	/**
	 * Replace the caption value and update captiontag_content as well
	 *
	 * @since 1.07
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 * @param	string	New value for Caption
	 *
	 * @return	array	item_values with updated 'caption' and 'captiontag_content'
	 */
	private static function _update_caption( $item_values, $new_caption ) {
		$old_caption = $item_values['caption'];
		$item_values['caption'] = $new_caption;

		if ( !empty( $item_values['captiontag_content'] ) ) {
			$item_values['captiontag_content'] = str_replace( $old_caption, $new_caption, $item_values['captiontag_content'] );
		} else {
			if ( $item_values['captiontag'] ) {
				$item_values['captiontag_content'] = '<' . $item_values['captiontag'] . " class='wp-caption-text gallery-caption' id='" . $item_values['selector'] . '-' . $item_values['attachment_ID'] . "'>\n\t\t" . $new_caption . "\n\t</" . $item_values['captiontag'] . ">\n";
			} else {
				$item_values['captiontag_content'] = $new_caption;
			}
		}

		return $item_values;
	} // _update_caption

	/**
	 * MLA Gallery Item Values
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_item_values_filter( $item_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_values_filter $item_values = ' . var_export( $item_values, true ), 0 );

		/*
		 * We use a shortcode parameter of our own to apply our filters on a gallery-by-gallery basis,
		 * leaving other [mla_gallery] instances untouched. If the "my_filter" parameter is not present,
		 * we have nothing to do.
		 */		
		if ( ! isset( self::$shortcode_attributes['my_filter'] ) ) {
			return $item_values; // leave them unchanged
		}

		if ( 'format terms' == self::$shortcode_attributes['my_filter'] ) {
			$object_terms = wp_get_object_terms ( absint( $item_values['attachment_ID'] ), 'attachment_category', array( 'fields' => 'slugs' ) );
			$item_values['terms:attachment_category'] = implode( ' ', $object_terms );
			$item_values = self::_update_caption( $item_values, implode( ' ', $object_terms ) );

		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_values_filter terms = ' . var_export( $item_values['terms:attachment_category'], true ), 0 );
		}

		/*
		 * For this first example, we will reformat the 'date' value as d/m/Y.
		 */
		if ( 'format date' == self::$shortcode_attributes['my_filter'] ) {

			/*
			 * Default format is YYYY-MM-DD HH:MM:SS (HH = 00 - 23), or 'Y-m-d H:i:s'
			 * Convert to UNIX timestamp so any reformat is possible
			 */
			$old_date = $item_values['date'];
			$timestamp = mktime( substr( $old_date, 11, 2 ), substr( $old_date, 14, 2 ), substr( $old_date, 17, 2 ), substr( $old_date, 5, 2 ), substr( $old_date, 8, 2 ), substr( $old_date, 0, 4 ) );

			/*
			 * Update the $item_values and pass them back from the filter.
			 * We must also update the caption because it was composed before this filter is called.
			 */
			$item_values['date'] = date( 'd/m/Y', $timestamp );
			$item_values = self::_update_caption( $item_values,$item_values['description'] . ', ' . $item_values['date'] );

			/*
			 * This alternative generates a "clickable" caption value
			 * linked directly to the item's attached file.
			 */
			//$item_values = self::_update_caption( $item_values, sprintf( '<a href="%1$s">%2$s<br>%3$s</a>', $item_values['file_url'], $item_values['title'], $item_values['date'] ) );

			return $item_values;
		}

		/*
		 * The second example adds a formatted file size element to the existing caption.
		 */
		if ( 'file size' == self::$shortcode_attributes['my_filter'] ) {

			/*
			 * Compose the file size in different formats.
			 *
			 * You can use MLAShortcodes::mla_get_data_source() to get anything available.
			 */
			$my_setting = array(
				'data_source' => 'file_size',
				'option' => 'raw'
			);
			$file_size = (float) MLAShortcodes::mla_get_data_source( $item_values['attachment_ID'], 'single_attachment_mapping', $my_setting, NULL );

			if ( 1048576 < $file_size ) {
				$file_size = number_format( ($file_size/1048576), 3 ).' MB';
			} elseif ( 10240 < $file_size ) {
				$file_size = number_format( ($file_size/1024), 3 ).' KB';
			} else {
				$file_size = number_format( $file_size );
			}

			/*
			 * Compose a new caption, adding the file size.
			 */
			return self::_update_caption( $item_values, sprintf( '%1$s<br>Size: %2$s', $item_values['caption'], $file_size ) );
		}

		/*
		 * Our third example changes taxonomy terms into links to term-specific archive pages.
		 */
		if ( 'term gallery' == self::$shortcode_attributes['my_filter'] ) {
			/*
			 * Use the "my_href" parameter to link to a static page,
			 * passing the taxanomy and term as query arguments.
			 */
			if ( isset( self::$shortcode_attributes['my_href'] ) ) {
				$my_href = self::$shortcode_attributes['my_href'];
			} else {
				$my_href = '';
			}

			/*
			 * Collect non-empty term lists, convert to slugs,
			 * make into links, replace $item_values
			 */
			foreach ($item_values as $key => $value ) {
				if ( ( 'terms:' == substr( $key, 0, 6 ) ) && ( ! empty( $value ) ) ) {
					$taxonomy = substr( $key, 6 );
					$value = str_replace( '&#8217;', "'", $value );
					$value = str_replace( '&rsquo;', "'", $value );
					$terms = array_map( 'trim', explode( ',', $value ) );
					$term_links = array();
					foreach( $terms as $term_name ) {
						//$term_object = get_term_by( 'name', $term_name, $taxonomy );
						$term_object = get_term_by( 'name', html_entity_decode( $term_name ), $taxonomy );

						if ( empty( $my_href ) ) {
							$term_links[] = sprintf( '<a href=%1$s/%2$s/%3$s>%4$s,</a>', get_site_url(), $taxonomy, $term_object->slug, esc_html( $term_name ) );
						} else {
							$term_links[] = sprintf( '<a href=%1$s/%2$s?my_taxonomy=%3$s&my_term=%4$s>%5$s</a>', get_site_url(),$my_href, $taxonomy, $term_object->slug, esc_html( $term_name ) );
						}
					}

					$item_values[ $key ] = implode( ' ', $term_links );
				}
			}

			return $item_values;
		}

		/*
		 * For the fourth example, we compose a URL to allow file deletion and add it to the caption.
		 */
		if ( 'allow file deletion' == self::$shortcode_attributes['my_filter'] ) {
			$id = (integer) $item_values['attachment_ID'];
			if ( current_user_can( 'delete_post', $id ) ) { 
				// Compose a new caption, adding the deletion link.
				$mla_link_href = "{$item_values['page_url']}?attachment_ID={$id}";
				$item_values = self::_update_caption( $item_values, sprintf( '%1$s<br><a href="%2$s" title="Click to delete">Delete this file</a>', $item_values['base_file'], $mla_link_href ) );
			} else {
				$item_values = self::_update_caption( $item_values, sprintf( '%1$s', $item_values['base_file'] ) );
			}
		}

		/*
		 * For the fifth example, we compose a caption with "Inserted in" links.
		 */
		if ( 'show post inserts' == self::$shortcode_attributes['my_filter'] ) {
			 // You can use MLAShortcodes::mla_get_data_source() to get anything available.
			$my_setting = array(
				'data_source' => 'inserted_in',
				'option' => 'raw'
			);
			$inserted_in = MLAShortcodes::mla_get_data_source( $item_values['attachment_ID'], 'single_attachment_mapping', $my_setting, NULL );

			if ( ' ' != $inserted_in ) {
				/*
				 * Break the information down:
				 * matches[1] => post/page Title 
				 * matches[2] => post/page post_type 
				 * matches[3] => post/page ID
				 */
				$my_posts = array();
				foreach ( (array) $inserted_in as $insert ) {
					if ( preg_match( '/(.*) \(([^ ]*) (\d+)\)/', $insert, $matches ) ) {
						// index on post ID to remove duplicates
						$my_posts[ $matches[3] ] = $matches[1];
					} // match
				} // each insert

				// Build the replacement caption
				$my_caption = NULL;
				foreach ( (array) $my_posts as $ID => $title ) {
					if ( empty( $my_caption ) ) {
						$my_caption = sprintf( 'Posted in: <a href="%1$s/?p=%2$d">%3$s</a>', $item_values['site_url'], $ID, $title );
					} else {
						$my_caption .= sprintf( ', <a href="%1$s/?p=%2$d">%3$s</a>', $item_values['site_url'], $ID, $title );
					}
				} // each post

				if ( ! empty( $my_caption ) ) {
					$item_values = self::_update_caption( $item_values, $my_caption );
				}
			} // has inserts
		}

		/*
		 * For our final example, we will add to the $item_values['caption'] value an unordered list
		 * of the custom fields populated for each gallery item. We use a shortcode parameter of our
		 * own to do this on a gallery-by-gallery basis, leaving other [mla_gallery] instances untouched.
		 */
		if ( 'all custom' != self::$shortcode_attributes['my_filter'] )
			return $item_values; // leave them unchanged

		/*
		 * Preserve the existing caption, if present
		 */
		$my_caption = '';
		if ( ! empty( $item_values['caption'] ) )
			$my_caption .= $item_values['caption'] . "<br />\r\n";

		/*
		 * Retrieve the custom fields for this item, if any,
		 * and extract the values we are interested in.
		 */
		$custom_fields = array();
		$post_meta = get_metadata( 'post', $item_values['attachment_ID'] );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_values_filter $post_meta = ' . var_export( $post_meta, true ), 0 );

		if ( is_array( $post_meta ) ) {
			foreach ( $post_meta as $post_meta_key => $post_meta_value ) {
				if ( empty( $post_meta_key ) )
					continue;

				/*
				 * WordPress stores several of its own values as custom fields, which we will skip.
				 * Some of the values you might find useful are:
				 * _wp_attached_file, _wp_attachment_metadata, and _wp_attachment_image_alt
				 */
				if ( '_' == $post_meta_key{0} )
						continue;

				/*
				 * At this point, every value is an array; one element per instance of the key.
				 * We'll test anyway, just to be sure, then convert single-instance values to a scalar.
				 * Metadata array values are serialized for storage in the database, so we might have to
				 * unserialize them before processing them as an array.
				 */
				if ( is_array( $post_meta_value ) ) {
					if ( count( $post_meta_value ) == 1 )
						$post_meta_value = maybe_unserialize( $post_meta_value[0] );
					else
						foreach ( $post_meta_value as $single_key => $single_value )
							$post_meta_value[ $single_key ] = maybe_unserialize( $single_value );
				}

				$custom_fields[ $post_meta_key ] = $post_meta_value;
			} // foreach $post_meta
		}
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_values_filter $custom_fields = ' . var_export( $custom_fields, true ), 0 );

		/*
		 * Don't alter the caption if there are no custom fields to display
		 */
		if ( empty( $custom_fields ) )
			return $item_values;

		/*
		 * Add the definition list to the caption
		 */
		$my_caption .= "<dl class=\"custom_field\">\r\n";
		foreach ( $custom_fields as $key => $value ) {
			$my_caption .= "<dt class=\"name\">{$key}</dt>\r\n";
			$my_caption .= "<dd class=\"value\">{$value}</dd>\r\n";
		} // foreach custom field
		$my_caption .= "</dl>";

		/*
		 * Update the $item_values and pass them back from the filter.
		 */
		$item_values = self::_update_caption( $item_values, $my_caption );
		return $item_values;
	} // mla_gallery_item_values_filter

	/**
	 * MLA Gallery Item Template
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_item_template_filter( $item_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_template_filter $item_template = ' . var_export( $item_template, true ), 0 );

		return $item_template;
	} // mla_gallery_item_template_filter

	/**
	 * MLA Gallery Item Parse
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_item_parse_filter( $html_markup, $item_template, $item_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_parse_filter $item_template = ' . var_export( $item_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_item_parse_filter $item_values = ' . var_export( $item_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_item_parse_filter

	/**
	 * MLA Gallery Row Close Values
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_row_close_values_filter( $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_close_values_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $markup_values;
	} // mla_gallery_row_close_values_filter

	/**
	 * MLA Gallery Row Close Template
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_row_close_template_filter( $row_close_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_close_template_filter $row_close_template = ' . var_export( $row_close_template, true ), 0 );

		return $row_close_template;
	} // mla_gallery_row_close_template_filter

	/**
	 * MLA Gallery Row Close Parse
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_row_close_parse_filter( $html_markup, $row_close_template, $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_close_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_close_parse_filter $row_close_template = ' . var_export( $row_close_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_row_close_parse_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_row_close_parse_filter

	/**
	 * MLA Gallery Close Values
	 *
	 * @since 1.00
	 *
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated substitution parameter name => value pairs
	 */
	public static function mla_gallery_close_values_filter( $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_close_values_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $markup_values;
	} // mla_gallery_close_values_filter

	/**
	 * MLA Gallery Close Template
	 *
	 * @since 1.00
	 *
	 * @param	string	template used to generate the HTML markup
	 *
	 * @return	string	updated template
	 */
	public static function mla_gallery_close_template_filter( $close_template ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_close_template_filter $close_template = ' . var_export( $close_template, true ), 0 );

		return $close_template;
	} // mla_gallery_close_template_filter

	/**
	 * MLA Gallery Close Parse
	 *
	 * @since 1.00
	 *
	 * @param	string	HTML markup returned by the template parser
	 * @param	string	template used to generate the HTML markup
	 * @param	array	parameter_name => parameter_value pairs
	 *
	 * @return	array	updated HTML markup for gallery output
	 */
	public static function mla_gallery_close_parse_filter( $html_markup, $close_template, $markup_values ) {
		//error_log( 'MLAGalleryHooksExample::mla_gallery_close_parse_filter $html_markup = ' . var_export( $html_markup, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_close_parse_filter $close_template = ' . var_export( $close_template, true ), 0 );
		//error_log( 'MLAGalleryHooksExample::mla_gallery_close_parse_filter $markup_values = ' . var_export( $markup_values, true ), 0 );

		return $html_markup;
	} // mla_gallery_close_parse_filter

} // Class MLAGalleryHooksExample

/*
 * Install the filters at an early opportunity
 */
add_action('init', 'MLAGalleryHooksExample::initialize');
?>