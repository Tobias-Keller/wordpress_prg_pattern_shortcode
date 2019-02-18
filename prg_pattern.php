<?php
/**
 * Created with PhpStorm.
 * Project: tobier.de
 * User: Tobias Keller
 * Date: 27.08.2018
 *
 * Shortcode:            [prgpattern slug="{SEITENSLUG}" title="{LINKTITLE}" extern="{false}"]
 * Beispiel (Intern):    [prgpattern slug="impressum" title="Impressum"]
 * Beispiel (Extern):    [prgpattern slug="https://t3n.de" title="HipsterMagazin" extern="true"]
 *
 * Beschreibung:
 * Ein einfacher WordPress Shortcode um Links nach dem
 * Post Redirect Get (PRG) System zu implementieren
 *
 */
$use_prg = new prg_pattern();

class prg_pattern {

	public function __construct() {
		/* load redirect function */
		add_action( 'template_redirect', array( $this, 'prg_get_and_redirect' ) );

		/* add short code function */
		add_shortcode( 'prgpattern', array( $this, 'prg_pattern_form' ) );
	}

	/**
	 * Gets the short code attributes and returns the html form
	 *
	 * @param   array   shortcode attributes
	 * @return  string  HTML-Form with shortcode attributes
	 * */
	public function prg_pattern_form( $atts ){

		/* get shortcode attributes */
		$atts = shortcode_atts(
			array(
				'slug' => 'noFoo',
				'title' => 'noBob',
				'extern' => 'false'
			), $atts, 'prgpattern' );

		/* check if user wants external or internal redirect */
		if ( $atts['extern'] == 'true' ) {
			/* redirect to external url */
			$redirect_slug = esc_url( $atts['slug'] );
		} else {
			/* redirect to internal url */
			$redirect_slug = esc_url( home_url() . '/' . strtolower( $atts['slug'] ) );
		}

		/* form output */
		ob_start();
		?>
			<form method="POST">
				<button type="submit" name="prgpattern" value="<?php echo $redirect_slug; ?>"><?php echo $atts['title']; ?></button>
			</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Redirects to target url if url is set
	 *
	 * @param   string      The target url of prg_pattern_form
	 * @return  redirect    Redirects to the target url
	 */
	public function prg_get_and_redirect(){
		/* check if target url is set */
		if ( isset( $_POST['prgpattern'] ) ) {
			/* escaping url again */
			$slug = esc_url( $_POST['prgpattern'] );
			/* redirect to target url */
			wp_safe_redirect( $slug );
			exit();
		}
	}
}