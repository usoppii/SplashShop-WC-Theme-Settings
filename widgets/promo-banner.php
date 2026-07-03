<?php
if (! defined('ABSPATH')) exit;

class ShopChop_Promo_Banner extends \Elementor\Widget_Base
{
	public function get_name(): string
	{
		return 'shopchop-promo-banner';
	}

	public function get_title(): string
	{
		return esc_html__('Promo Banner', 'shopchop-theme-settings');
	}

	public function get_icon(): string
	{
		return 'eicon-banner';
	}

	public function get_categories(): array
	{
		return ['shopchop'];
	}

	public function get_keywords(): array
	{
		return ['promo', 'banner', 'sale', 'cta', 'campaign', 'offer'];
	}

	public function has_widget_inner_wrapper(): bool
	{
		return false;
	}

	protected function register_controls(): void
	{
		// ── Content ───────────────────────────────────────────────
		$this->start_controls_section('section_content', [
			'label' => esc_html__('Content', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('heading_text', [
			'label'   => esc_html__('Heading', 'shopchop-theme-settings'),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__('Summer Sale — Up to 50% Off', 'shopchop-theme-settings'),
		]);

		$this->add_control('description', [
			'label'   => esc_html__('Description', 'shopchop-theme-settings'),
			'type'    => \Elementor\Controls_Manager::TEXTAREA,
			'default' => esc_html__('Limited time only. Don\'t miss out on our biggest sale of the year.', 'shopchop-theme-settings'),
			'rows'    => 3,
		]);

		$this->end_controls_section();

		// ── CTA ───────────────────────────────────────────────────
		$this->start_controls_section('section_cta', [
			'label' => esc_html__('CTA Button', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('cta_label', [
			'label'   => esc_html__('Button Label', 'shopchop-theme-settings'),
			'type'    => \Elementor\Controls_Manager::TEXT,
			'default' => esc_html__('Shop Now', 'shopchop-theme-settings'),
		]);

		$this->add_control('cta_url', [
			'label'       => esc_html__('Button URL', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::URL,
			'placeholder' => 'https://',
		]);

		$this->end_controls_section();
	}

	protected function render(): void
	{
		$settings   = $this->get_settings_for_display();
		$heading    = $settings['heading_text'] ?? '';
		$desc       = $settings['description']  ?? '';
		$cta_label  = $settings['cta_label']    ?? '';
		$cta_url    = $settings['cta_url']['url']         ?? '';
		$cta_target = ($settings['cta_url']['is_external'] ?? '') === 'on' ? '_blank' : '_self';
		$cta_norel  = ($settings['cta_url']['nofollow']    ?? '') === 'on' ? 'nofollow' : '';
?>

		<div class="shopchop-promo-banner">
			<div class="homepage-container">
				<div class="promo-banner-inner">

					<div class="promo-banner-content">
						<?php if ($heading) : ?>
							<h2 class="promo-banner-heading"><?php echo esc_html($heading); ?></h2>
						<?php endif; ?>

						<?php if ($desc) : ?>
							<p class="promo-banner-description"><?php echo esc_html($desc); ?></p>
						<?php endif; ?>
					</div>

					<?php if ($cta_label && $cta_url) : ?>
						<div class="promo-banner-cta-wrap">
							<a
								href="<?php echo esc_url($cta_url); ?>"
								class="promo-banner-cta"
								target="<?php echo esc_attr($cta_target); ?>"
								<?php if ($cta_norel) : ?>rel="<?php echo esc_attr($cta_norel); ?>"<?php endif; ?>
							>
								<?php echo esc_html($cta_label); ?>
								<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path></svg>
							</a>
						</div>
					<?php endif; ?>

				</div>
			</div>
		</div>

<?php
	}
}
