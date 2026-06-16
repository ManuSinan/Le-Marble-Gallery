<?php
return [
    'Enquiry' => [
        'View' => ['enquiry', 'enquiry.list'],
        'Update' => ['enquiry.edit', 'enquiry.update'],
        'Delete' => ['enquiry.destroy'],
    ],

    'Order' => [
        'View' => ['order', 'order.list'],
        'Update' => ['order.view', 'order.update'],
        'Full Access' => ['order.full.access']
    ],

    'Report' => [
        'Business Overview' => ['report.business.overview'],
        'Publishers' => ['report.most.purchased.brands'],
        'Categories' => ['report.most.purchased.categories'],
        'Books' => ['report.most.purchased.products'],
        'Location' => ['report.location']
    ],

    'Book' => [
        'Stock' => ['stock', 'product.stocklist'],
        'View' => ['product', 'product.list', 'product.attribute', 'product.unit'],
        'Create' => ['product.create', 'product.copy', 'product.store'],
        'Update' => ['product.edit', 'product.update'],
        'Delete' => ['product.destroy'],
    ],

    'Attribute' => [
        'View' => ['attribute', 'attribute.list'],
        'Create' => ['attribute.store'],
        'Update' => ['attribute.edit', 'attribute.update'],
        'Delete' => ['attribute.destroy'],
    ],

    'Variant Option' => [
        'View' => ['variant.option', 'variant.option.list'],
        'Create' => ['variant.option.store'],
        'Update' => ['variant.option.edit', 'variant.option.update'],
        'Delete' => ['variant.option.destroy'],
    ],

    'Variant' => [
        'View' => ['variant', 'variant.list'],
        'Create' => ['variant.store'],
        'Update' => ['variant.edit', 'variant.update'],
        'Delete' => ['variant.destroy'],
    ],

    'Tax' => [
        'View' => ['tax', 'tax.list'],
        'Create' => ['tax.store'],
        'Update' => ['tax.edit', 'tax.update'],
        'Delete' => ['tax.destroy'],
    ],

    'Unit' => [
        'View' => ['unit', 'unit.list'],
        'Create' => ['unit.store'],
        'Update' => ['unit.edit', 'unit.update'],
        'Delete' => ['unit.destroy'],
    ],

    'Publisher' => [
        'View' => ['brand', 'brand.list'],
        'Create' => ['brand.store'],
        'Update' => ['brand.edit', 'brand.update'],
        'Delete' => ['brand.destroy'],
    ],

    'Category' => [
        'View' => ['category', 'category.list'],
        'Create' => ['category.store'],
        'Update' => ['category.edit', 'category.update'],
        'Delete' => ['category.destroy'],
    ],
 
    'Location' => [
        'View' => ['location', 'location.list'],
        'Create' => ['location.store'],
        'Update' => ['location.edit', 'location.update'],
        'Delete' => ['location.destroy'],
    ],

    'Pincode' => [
        'View' => ['pincode', 'pincode.list'],
        'Create' => ['pincode.store'],
        'Update' => ['pincode.edit', 'pincode.update'],
        'Delete' => ['pincode.destroy'],
    ],

    'State' => [
        'View' => ['state', 'state.list'],
        'Create' => ['state.store'],
        'Update' => ['state.edit', 'state.update'],
        'Delete' => ['state.destroy'],
    ],
 
    'Banner Slider' => [
        'View' => ['banner.slider', 'banner.slider.list'],
        'Create' => ['banner.slider.store'],
        'Update' => ['banner.slider.edit', 'banner.slider.update'],
        'Delete' => ['banner.slider.destroy'],
    ],

    'Promotional Banners' => [
        'View' => ['poster', 'poster.list'],
        'Create' => ['poster.store'],
        'Update' => ['poster.edit', 'poster.update'],
        'Delete' => ['poster.destroy'],
    ],

    'User' => [
        'View' => ['user', 'user.list'],
        'Create' => ['user.store'],
        'Update' => ['user.edit', 'user.update'],
        'Delete' => ['user.destroy'],
    ],


    'Permission' => [
        'View' => ['permission', 'permission.edit'],
        'Update' => ['permission.update'],
    ],

    'Role' => [
        'View' => ['role', 'role.list'],
        'Create' => ['role.store'],
        'Update' => ['role.edit', 'role.update'],
        'Delete' => ['role.destroy'],
    ],

    'Manage Store' => [
        'Manage' => ['manage.store', 'option.update'],
    ],

    'Page' => [
        'About us Page' => ['about.us', 'option.update'],
        'Terms and Conditions Page' => ['about.us', 'terms.and.conditions'],
        'Privacy Policy Page' => ['privacy.policy', 'option.update'],
        'Return Policy Page' => ['return.policy', 'option.update'],
    ],
];
