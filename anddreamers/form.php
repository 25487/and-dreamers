<?php
if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)){
    $dataCheck = true;
	$data = [];
	$dataValues = [
		"title",
		"beschrijving",
		"size",
		"material",
		"hire-price",
		"buy-price",
		"beschikbaarheid"
	];
		
	for ($i=0; $i < count($dataValues); $i++) {
		if(!empty($_POST[$dataValues[$i]])){
			$data[$dataValues[$i]] = $_POST[$dataValues[$i]];
		} else {
			$dataCheck = false;
		}
	}

    if($dataCheck){
        $product_id = wp_insert_post( array(
            'post_title' => $data["title"],
            'post_type' => "product",
            'post_status' => "publish",
            'post_content' => $data["beschrijving"]
        ));

        wp_set_object_terms($product_id, 'Hoeden', 'product_cat');
        wp_set_object_terms($product_id, 'simple', 'product_type');

        $product = wc_get_product( $product_id );
        $attributes = [];
        $attributeName = [
            "Size",
            "Materials",
            "Beschikbaarheid",
            "Huur prijs",
            "Koop prijs"
        ];
        $attributeValues = [
            $data["size"],
            $data["material"],
            $data["beschikbaarheid"],
            "€" . $data["hire-price"],
            "€" . $data["buy-price"]
        ];

        foreach ($attributeNames as $index => $attributeName) {
            $attribute = new WC_Product_Attribute();
            $attribute->set_name($attributeName);
            $attribute->set_options(array($attributeValues[$index]));
            $attribute->set_visible(true);
            $attribute->set_variation(false);
            $attributes[] = $attribute;
        }

        $product->set_attributes($attributes);

        $product->save();
    }
}
?>