<?php
/**
 * File download support for [mla_gallery]
 *
 * @package Media Library Assistant
 * @since 2.32
 */

/**
 * Class MLA (Media Library Assistant) File Downloader provides file streaming to client.
 *
 * @package Media Library Assistant
 * @since 2.32
 */
class MLAFileDownloader {
	/**
	 * Log debug information if true
	 *
	 * @since 2.32
	 *
	 * @var boolean
	 */
	public static $mla_debug = false;

	/**
	 * Process secure file download
	 *
	 * Requires mla_download_file and mla_download_type in $_REQUEST.
	 *
	 * @since 2.32
	 *
	 * @return	void	echos file contents and calls exit();
	 */
	public static function mla_process_download_file() {
		self::_mla_debug_add( 'mla_process_download_file, REQUEST = ' . var_export( $_REQUEST, true ) );
		if ( isset( $_REQUEST['mla_download_file'] ) && isset( $_REQUEST['mla_download_type'] ) ) {
			if( ini_get( 'zlib.output_compression' ) ) { 
				ini_set( 'zlib.output_compression', 'Off' );
			}

			$file_name = $_REQUEST['mla_download_file'];

			header('Pragma: public'); 	// required
			header('Expires: 0');		// no cache
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Last-Modified: '.gmdate ( 'D, d M Y H:i:s', filemtime ( $file_name ) ).' GMT');
			header('Cache-Control: private',false);
			header('Content-Type: '.$_REQUEST['mla_download_type']);
			header('Content-Disposition: attachment; filename="'.basename( $file_name ).'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: '.filesize( $file_name ));	// provide file size
			header('Connection: close');

			readfile( $file_name );
			exit();
		} else {
			$message = __( 'ERROR', 'media-library-assistant' ) . ': ' . 'download argument(s) not set.';
			self::_mla_debug_add( $message );
		}

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml">';
		echo '<head>';
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
		echo '<title>Download Error</title>';
		echo '</head>';
		echo '';
		echo '<body>';
		echo $message;
		echo '</body>';
		echo '</html> ';
		exit();
	}

	/**
	 * Log debug information
	 *
	 * @since 2.32
	 *
	 * @param	string	$message Error message.
	 */
	private static function _mla_debug_add( $message ) {
		if ( self::$mla_debug ) {
			if ( class_exists( 'MLACore' ) ) {
				MLACore::mla_debug_add( $message );
			} else {
				error_log( $message, 0);
			}
		}
	}

	/**
	 * Abort the operation and exit
	 *
	 * @since 2.32
	 *
	 * @param	string	$message Error message.
	 * @param	string	$title Optional. Error title. Default empty.
	 * @param	integer	$response Optional. HTML response code. Default 500.

	 * @return	void	echos page content and calls exit();
	 */
	private static function _mla_die( $message, $title = '', $response = 500 ) {
		self::_mla_debug_add( __LINE__ . " _mla_die( '{$message}', '{$title}', '{$response}' )" );
		exit();
	}

	/**
	 * Log the message and return error message array
	 *
	 * @since 2.32
	 *
	 * @param	string	$message Error message.
	 * @param	string	$line Optional. Line number in the caller.
	 *
	 * @return	 array( 'error' => message )
	 */
	private static function _mla_error_return( $message, $line = '' ) {
		self::_mla_debug_add( $line . " MLAFileDownloader::_mla_error_return '{$message}'" );
		return array( 'error' => $message );
	}
} // Class MLAFileDownloader
?>