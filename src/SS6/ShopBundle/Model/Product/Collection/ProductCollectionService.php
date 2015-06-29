<?php

namespace SS6\ShopBundle\Model\Product\Collection;

class ProductCollectionService {

	/**
	 * @param \SS6\ShopBundle\Model\Product\Product $products
	 * @param \SS6\ShopBundle\Model\Image\Image $imagesByProductId
	 * @return array
	 */
	public function getImagesIndexedByProductId(array $products, array $imagesByProductId) {
		$imagesOrNullByProductId = [];

		foreach ($products as $product) {
			if (array_key_exists($product->getId(), $imagesByProductId)) {
				$imagesOrNullByProductId[$product->getId()] = $imagesByProductId[$product->getId()];
			} else {
				$imagesOrNullByProductId[$product->getId()] = null;
			}
		}

		return $imagesOrNullByProductId;
	}
}
