<?php
if (! defined('ABSPATH')) exit;

class ShopChop_Category_Grid extends \Elementor\Widget_Base
{

	public function get_name(): string
	{
		return 'shopchop-category-grid';
	}

	public function get_title(): string
	{
		return esc_html__('Category Grid', 'shopchop-theme-settings');
	}

	public function get_icon(): string
	{
		return 'eicon-gallery-grid';
	}

	public function get_categories(): array
	{
		return ['shopchop'];
	}

	public function get_keywords(): array
	{
		return ['category', 'grid', 'shop', 'products'];
	}

	public function has_widget_inner_wrapper(): bool
	{
		return false;
	}

	protected function is_dynamic_content(): bool
	{
		return true;
	}

	protected function register_controls(): void
	{

		// ── Heading ──────────────────────────────────────────────
		$this->start_controls_section('section_heading', [
			'label' => esc_html__('Heading', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('heading_text', [
			'label'       => esc_html__('Heading Text', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::TEXT,
			'default'     => esc_html__('Shop by Category', 'shopchop-theme-settings'),
			'placeholder' => esc_html__('e.g. Shop by Category', 'shopchop-theme-settings'),
		]);

		$this->end_controls_section();

		// ── Query ────────────────────────────────────────────────
		$this->start_controls_section('section_query', [
			'label' => esc_html__('Query', 'shopchop-theme-settings'),
			'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
		]);

		$this->add_control('orderby', [
			'label'   => esc_html__('Order By', 'shopchop-theme-settings'),
			'type'    => \Elementor\Controls_Manager::SELECT,
			'default' => 'menu_order',
			'options' => [
				'menu_order' => esc_html__('Custom Order (set per-category in WP Admin)', 'shopchop-theme-settings'),
				'name'       => esc_html__('Alphabetical', 'shopchop-theme-settings'),
				'count'      => esc_html__('Most Products', 'shopchop-theme-settings'),
			],
		]);

		$this->add_control('limit', [
			'label'       => esc_html__('Max Parent Categories', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'default'     => 0,
			'min'         => 0,
			'description' => esc_html__('0 = show all.', 'shopchop-theme-settings'),
		]);

		$this->add_control('subcat_limit', [
			'label'       => esc_html__('Max Subcategories Shown', 'shopchop-theme-settings'),
			'type'        => \Elementor\Controls_Manager::NUMBER,
			'default'     => 4,
			'min'         => 1,
			'max'         => 10,
			'description' => esc_html__('Shows "View all" link if subcategories exceed this number.', 'shopchop-theme-settings'),
		]);

		$this->add_control('hide_empty', [
			'label'        => esc_html__('Hide Empty Categories', 'shopchop-theme-settings'),
			'type'         => \Elementor\Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]);

		$this->end_controls_section();
	}

	protected function render(): void
	{
		$settings     = $this->get_settings_for_display();
		$heading      = $settings['heading_text'] ?? '';
		$orderby      = $settings['orderby']      ?? 'menu_order';
		$limit        = (int) ($settings['limit']        ?? 0);
		$subcat_limit = (int) ($settings['subcat_limit'] ?? 4);
		$hide_empty   = $settings['hide_empty']   === 'yes';

		$args = [
			'taxonomy'   => 'product_cat',
			'orderby'    => $orderby,
			'order'      => $orderby === 'count' ? 'DESC' : 'ASC',
			'hide_empty' => $hide_empty,
			'parent'     => 0,
			'exclude'    => [get_option('default_product_cat')],
		];

		if ($limit > 0) {
			$args['number'] = $limit;
		}

		$parents = get_terms($args);

		if (is_wp_error($parents) || empty($parents)) return;
?>

		<div class="shopchop-category-grid">
			<div class="homepage-container">
				<?php if ($heading) : ?>
					<h2 class="category-heading"><?php echo esc_html($heading); ?></h2>
				<?php endif; ?>
				<div class="category-grid-wrapper">

					<?php foreach ($parents as $cat) :
						$cat_link  = get_term_link($cat);
						$thumbnail = get_term_meta($cat->term_id, 'thumbnail_id', true);
						$img_url   = $thumbnail
							? wp_get_attachment_image_url($thumbnail, 'medium')
							: wc_placeholder_img_src('medium');

						$subcats = get_terms([
							'taxonomy'   => 'product_cat',
							'parent'     => $cat->term_id,
							'orderby'    => $orderby,
							'order'      => $orderby === 'count' ? 'DESC' : 'ASC',
							'hide_empty' => $hide_empty,
						]);

						$has_more = ! is_wp_error($subcats) && count($subcats) > $subcat_limit;
						$subcats_shown = (! is_wp_error($subcats) && ! empty($subcats))
							? array_slice($subcats, 0, $subcat_limit)
							: [];
					?>

						<div class="category-grid-card group">

							<a href="<?php echo esc_url($cat_link); ?>"
								class="cat-image-wrapper"
								tabindex="-1"
								aria-hidden="true">
								<img src="<?php echo esc_url($img_url); ?>"
									alt="<?php echo esc_attr($cat->name); ?>"
									loading="lazy"
									class="cat-image">
							</a>

							<div class="cat-grid-info">

								<a href="<?php echo esc_url($cat_link); ?>" class="parent-cat-name-link">
									<?php echo esc_html($cat->name); ?>
								</a>

								<?php if (! empty($subcats_shown)) : ?>
									<ul class="subcategory-list">
										<?php foreach ($subcats_shown as $sub) : ?>
											<li>
												<a href="<?php echo esc_url(get_term_link($sub)); ?>" class="subcategory-list-link">
													<?php echo esc_html($sub->name); ?>
												</a>
											</li>
										<?php endforeach; ?>

										<?php if ($has_more) : ?>
											<li>
												<a href="<?php echo esc_url($cat_link); ?>" class="subcategory-list-all">
													<?php esc_html_e('View all', 'shopchop-theme-settings'); ?>
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 256 256"><path d="M181.66,133.66l-80,80a8,8,0,0,1-11.32-11.32L164.69,128,90.34,53.66a8,8,0,0,1,11.32-11.32l80,80A8,8,0,0,1,181.66,133.66Z"></path></svg>
												</a>
											</li>
										<?php endif; ?>
									</ul>
								<?php endif; ?>

							</div>

						</div>

					<?php endforeach; ?>
				</div>
			</div>
		</div>

<?php
	}
}
