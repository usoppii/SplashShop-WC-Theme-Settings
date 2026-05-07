<?php
if (! defined('ABSPATH')) exit;

class ShopChop_Hero_Carousel extends \Elementor\Widget_Base
{

	public function get_name(): string
	{
		return 'shopchop-hero-carousel';
	}

	public function get_title(): string
	{
		return esc_html__('Hero Carousel', 'shopchop-theme-settings');
	}

	public function get_icon(): string
	{
		return 'eicon-slideshow';
	}

	public function get_categories(): array
	{
		return ['shopchop'];
	}

	public function get_keywords(): array
	{
		return ['hero', 'carousel', 'slider', 'banner'];
	}

	public function has_widget_inner_wrapper(): bool
	{
		return false;
	}

	protected function is_dynamic_content(): bool
	{
		return false;
	}

	protected function register_controls(): void
	{

		// ── Slides ──────────────────────────────────────────
		$this->start_controls_section('section_slides', [
			'label' => esc_html__('Slides', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control('slide_title', [
			'label'       => esc_html__('Slide Title', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'placeholder' => esc_html__('e.g. Summer Sale Banner', 'shopchop-theme-settings'),
			'description' => esc_html__('Used for accessibility (screen readers) and as the link label. Not displayed visually.', 'shopchop-theme-settings'),
		]);

		$repeater->add_control('desktop_image', [
			'label'       => esc_html__('Desktop Image', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::MEDIA,
			'default'     => ['url' => \Elementor\Utils::get_placeholder_image_src()],
			'description' => esc_html__('Recommended: 1920 × 620px, WebP, max 300KB.', 'shopchop-theme-settings'),
		]);

		$repeater->add_control('mobile_image', [
			'label'       => esc_html__('Mobile Image', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::MEDIA,
			'description' => esc_html__('Recommended: 768 × 420px, WebP, max 150KB. Falls back to desktop image if empty.', 'shopchop-theme-settings'),
		]);

		$repeater->add_control('slide_link', [
			'label'       => esc_html__('Slide Link', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::URL,
			'placeholder' => 'https://',
		]);

		$this->add_control('slides', [
			'label'       => esc_html__('Slides', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => [
				['desktop_image' => ['url' => \Elementor\Utils::get_placeholder_image_src()]],
			],
			'title_field' => '{{{ slide_title || "Slide" }}}',
		]);

		$this->end_controls_section();

		// ── Layout ──────────────────────────────────────────
		$this->start_controls_section('section_layout', [
			'label' => esc_html__('Layout', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('desktop_height', [
			'label'      => esc_html__('Desktop Height', 'shopchop-theme-settings'),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', 'vh'],
			'range'      => [
				'px' => ['min' => 300, 'max' => 1000, 'step' => 10],
				'vh' => ['min' => 20,  'max' => 100,  'step' => 1],
			],
			'default'    => ['unit' => 'px', 'size' => 620], // recommended desktop hero height
		]);

		$this->add_control('mobile_height', [
			'label'      => esc_html__('Mobile Height', 'shopchop-theme-settings'),
			'type'       => \Elementor\Controls_Manager::SLIDER,
			'size_units' => ['px', 'vh'],
			'range'      => [
				'px' => ['min' => 200, 'max' => 700, 'step' => 10],
				'vh' => ['min' => 20,  'max' => 100,  'step' => 1],
			],
			'default'    => ['unit' => 'px', 'size' => 420], // recommended mobile hero height
		]);

		$this->end_controls_section();

		// ── Carousel Settings ────────────────────────────────
		$this->start_controls_section('section_carousel', [
			'label' => esc_html__('Carousel', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('autoplay', [
			'label'        => esc_html__('Autoplay', 'shopchop-theme-settings'),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'label_on'     => esc_html__('Yes', 'shopchop-theme-settings'),
			'label_off'    => esc_html__('No', 'shopchop-theme-settings'),
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->add_control('autoplay_delay', [
			'label'     => esc_html__('Autoplay Delay (ms)', 'shopchop-theme-settings'),
			'type'      => \Elementor\Controls_Manager::NUMBER,
			'default'   => 4000,
			'min'       => 1000,
			'max'       => 10000,
			'step'      => 500,
			'condition' => ['autoplay' => 'yes'],
		]);

		$this->add_control('loop', [
			'label'        => esc_html__('Loop', 'shopchop-theme-settings'),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->add_control('show_arrows', [
			'label'        => esc_html__('Show Arrows', 'shopchop-theme-settings'),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->add_control('show_pagination', [
			'label'        => esc_html__('Show Dots', 'shopchop-theme-settings'),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->end_controls_section();
	}

	protected function render(): void
	{
		$settings = $this->get_settings_for_display();
		$slides   = $settings['slides'] ?? [];

		if (empty($slides)) return;

		$desktop_h  = ($settings['desktop_height']['size'] ?? 620) . ($settings['desktop_height']['unit'] ?? 'px');
		$mobile_h   = ($settings['mobile_height']['size']  ?? 380) . ($settings['mobile_height']['unit']  ?? 'px');
		$is_multi   = count($slides) > 1;
		$autoplay   = $settings['autoplay']        === 'yes';
		$loop       = $settings['loop']            === 'yes';
		$arrows     = $settings['show_arrows']     === 'yes';
		$pagination = $settings['show_pagination'] === 'yes';
		$delay      = (int) ($settings['autoplay_delay'] ?? 4000);
		$widget_id  = $this->get_id();

		$swiper_config = wp_json_encode([
			'loop'       => $loop,
			'autoplay'   => $autoplay ? ['delay' => $delay, 'disableOnInteraction' => false] : false,
			'pagination' => $pagination ? ['el' => '.swiper-pagination', 'clickable' => true] : false,
			'navigation' => $arrows ? ['nextEl' => '.swiper-button-next', 'prevEl' => '.swiper-button-prev'] : false,
		]);
?>

		<style>
			#shopchop-hero-<?php echo esc_attr($widget_id); ?> {
				--hero-h-desktop: <?php echo esc_attr($desktop_h); ?>;
				--hero-h-mobile: <?php echo esc_attr($mobile_h); ?>;
			}
		</style>

		<div id="shopchop-hero-<?php echo esc_attr($widget_id); ?>" class="shopchop-hero-carousel">

			<?php if ($is_multi) : ?>
				<div class="swiper shopchop-hero-swiper" data-swiper="<?php echo esc_attr($swiper_config); ?>">
					<div class="swiper-wrapper">
					<?php endif; ?>

					<?php foreach ($slides as $index => $slide) :
						$title       = ! empty($slide['slide_title']) ? $slide['slide_title'] : '';
						$desktop_url = $slide['desktop_image']['url'] ?? '';
						$mobile_url  = ! empty($slide['mobile_image']['url']) ? $slide['mobile_image']['url'] : $desktop_url;
						$link_url    = $slide['slide_link']['url'] ?? '';
						$target      = ! empty($slide['slide_link']['is_external']) ? '_blank' : '_self';
						$rel         = 'noopener' . (! empty($slide['slide_link']['nofollow']) ? ' noreferrer nofollow' : '');
						$loading     = $index === 0 ? 'eager' : 'lazy';
					?>
						<div class="<?php echo $is_multi ? 'swiper-slide ' : ''; ?>shopchop-hero-slide">
							<?php if ($link_url) : ?>
								<a href="<?php echo esc_url($link_url); ?>"
									title="<?php echo esc_attr($title); ?>"
									target="<?php echo esc_attr($target); ?>"
									rel="<?php echo esc_attr($rel); ?>"
									class="shopchop-hero-link"
									<?php if ($title) echo 'aria-label="' . esc_attr($title) . '"'; ?>>
									<?php if ($title) : ?>
										<span class="sr-only"><?php echo esc_html($title); ?></span>
									<?php endif; ?>
								<?php endif; ?>

								<picture class="shopchop-hero-picture"
									<?php if ($title && ! $link_url) echo 'aria-label="' . esc_attr($title) . '"'; ?>>
									<source media="(max-width: 767px)" srcset="<?php echo esc_url($mobile_url); ?>">
									<img src="<?php echo esc_url($desktop_url); ?>"
										alt="<?php echo esc_attr($title); ?>"
										loading="<?php echo esc_attr($loading); ?>"
										class="shopchop-hero-img">
								</picture>

								<?php if ($link_url) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>

					<?php if ($is_multi) : ?>
					</div><!-- .swiper-wrapper -->
					<?php if ($pagination) : ?><div class="swiper-pagination"></div><?php endif; ?>
					<?php if ($arrows) : ?>
						<div class="swiper-button-prev" title="Previous Slide"></div>
						<div class="swiper-button-next" title="Next Slide"></div>
					<?php endif; ?>
				</div><!-- .swiper -->
			<?php endif; ?>

		</div><!-- .shopchop-hero-carousel -->


<?php
	}
}
