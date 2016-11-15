<?php

class Video_List_Table extends WP_List_Table {

	function __construct() {
		global $status, $page;

		// Set parent defaults
		parent::__construct( array(
				'singular'  => 'video',     // singular name of the listed records
				'plural'    => 'videos',    // plural name of the listed records
				'ajax'      => false        // does this table support ajax?
			) );

    // Add helper JS
    wp_enqueue_script( 'record_video_admin_table',
      plugin_dir_url( __FILE__ ) . '../js/record_video_admin_table.js', array( 'jquery' ), '1.3', true );

	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
		  case 'filename':
		  case 'ip':
		  case 'uploaded_at':
		  	return $item[$column_name];
		  default:
		  	return print_r( $item, true ); // Show the whole array for troubleshooting purposes
		}
	}

	function column_filename( $item ) {

		// Build row actions
		$actions = array(
			'download'  => sprintf( '<a href="http://vidrack-media.s3.amazonaws.com/%s" download>Download</a>', $item['filename'] ),
			'delete'    => sprintf( '<a class="wp-video-capture-delete-video" href="?page=%s&action=%s&video=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id'] ),
		);

		// Return the title contents
		return sprintf( '<span class="vidrack-filename">%1$s</span> <span style="color:silver">(id:%2$s)</span>%3$s',
			$item['filename'],
			$item['id'],
			$this->row_actions( $actions )
		);
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			/*$1%s*/ $this->_args['singular'],
			/*$2%s*/ $item['id']
		);
	}

	function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />', // Render a checkbox instead of text
			'filename'    => 'Filename',
			'ip'          => 'IP',
			'uploaded_at' => 'Uploaded at'
		);
		return $columns;
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'filename'    => array( 'filename', false ),
			'ip'          => array( 'ip', false ),
			'uploaded_at' => array( 'uploaded_at', true ) // true means it's already sorted
		);
		return $sortable_columns;
	}

	function get_bulk_actions() {
		$actions = array(
			'delete'    => 'Delete'
		);
		return $actions;
	}

	function process_bulk_action() {

		if ( 'delete' === $this->current_action() ) {
			global $wpdb;
			if ( isset($_REQUEST['video']) ) {
				if ( is_array( $_REQUEST['video'] ) ) {
					// Delete multiple videos
					foreach ( $_REQUEST['video'] as &$video ) {
						$wpdb->delete( $wpdb->prefix . 'video_capture', array( 'id' => $video ) );
					}
				} else {
					// Delete a single video
					$wpdb->delete( $wpdb->prefix . 'video_capture', array( 'id' => $_REQUEST['video'] ) );
				}
			}
    }

	}

	function prepare_items() {
		global $wpdb;

		// Bulk action handler
		$this->process_bulk_action();

		// Initial query
		$query = "SELECT id, filename, ip, uploaded_at FROM " . $wpdb->prefix . "video_capture";

		// Parameters that are going to be used to order the result
		$orderby = !empty( $_GET["orderby"] ) ? mysql_real_escape_string( $_GET["orderby"] ) : 'uploaded_at';
		$order = !empty( $_GET["order"] ) ? mysql_real_escape_string( $_GET["order"] ) : 'DESC';
		if ( !empty( $orderby ) & !empty( $order ) ) { $query.=' ORDER BY '.$orderby.' '.$order; }

		// Number of elements in your table?
		$total_items = $wpdb->query( $query ); //return the total number of affected rows

		// How many to display per page?
		$per_page = 20;

		// Which page is this?
		$paged = !empty( $_GET["paged"] ) ? mysql_real_escape_string( $_GET["paged"] ) : '';

		// Page Number
		if ( empty( $paged ) || !is_numeric( $paged ) || $paged<=0 ) { $paged=1; }

		// How many pages do we have in total?
		$total_pages = ceil( $total_items/$per_page );

		// Adjust the query to take pagination into account
		if ( !empty( $paged ) && !empty( $per_page ) ) {
			$offset = ( $paged - 1 ) * $per_page;
			$query .= ' LIMIT ' . (int)$offset . ',' . (int)$per_page;
		}

		// Register the pagination
		$this->set_pagination_args( array(
				"total_items" => $total_items,
				"total_pages" => $total_pages,
				"per_page" => $per_page,
			) );

		// Register the Columns
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		// Fetch the items
		$this->items = $wpdb->get_results( $query, ARRAY_A );
	}

}

?>
