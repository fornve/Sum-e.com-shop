<?php
	class SitemapController extends Controller
	{
		function Index()
		{
		}

		function XML()
		{
			$sitemap = new Sitemap();

			$elements = SitemapController::Generate();
			foreach( $elements as $element )
			{
				$sitemap->AddElement( $element );
			}

			echo $sitemap->GenerateXML();
		}

		private static function Generate()
		{
			// categories
			$categories = Category::GetAll();
			if( $categories ) foreach ( $categories as $category )
			{
				$element = new SitemapUrl();
				$element->url =  "http://{$_SERVER['SERVER_NAME']". Url::Encode( "/Category/View/{$category->id}}/{$category->title}" );
				$element->lastmod = time();
				$element->changefreq = 'monthly';
				$element->priority = 0.8;
				$elements[] = $element;
			}

			// products
			$products = Product::GetAllVisible();
			if( $products ) foreach ( $products as $product )
			{
				$element = new SitemapUrl();
				$element->url =  "http://{$_SERVER['SERVER_NAME']". Url::Encode( "/Product/View/{$product->id}}/{$product->title}" );
				$element->lastmod = time();
				$element->changefreq = 'monthly';
				$element->priority = 0.9;
				$elements[] = $element;
			}

			// editorial content
			$pages = Page::GetAllVisible();
			if( $pages ) foreach ( $pages as $page )
			{
				$element = new SitemapUrl();
				$element->url =  "http://{$_SERVER['SERVER_NAME']". Url::Encode( "/Page/View/{$page->id}}/{$page->title}" );
				$element->lastmod = time();
				$element->changefreq = 'monthly';
				$element->priority = 0.7;
				$elements[] = $element;
			}

			// static pages
			$pages = StaticPageController::GetAllVisible()
			if( $pages ) foreach ( $pages as $page )
			{
				$element = new SitemapUrl();
				$element->url =  "http://{$_SERVER['SERVER_NAME']". Url::Encode( "/Page/View/{$page->id}}/{$page->title}" );
				$element->lastmod = time();
				$element->changefreq = 'monthly';
				$element->priority = 0.5;
				$elements[] = $element;
			}

		
		}
	}
