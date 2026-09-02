<?php
/**
 * JSON-LD structured data.
 *
 * Always emits the sitewide MedicalBusiness graph. Pages may additionally set,
 * before including header.php:
 *
 *   $breadcrumbs  array of ['label' => ..., 'url' => site-relative path]
 *                 — the same list the visible breadcrumb renders, so the two
 *                   never disagree, which is what search engines check for.
 *   $faq_schema   array of [question, answer] pairs matching a visible FAQ
 *   $service_schema  ['name' => ..., 'description' => ...] on service pages
 */

$graph = [];

/* ------------------------------------------------ sitewide organization */
$graph[] = [
    '@type'       => ['MedicalBusiness', 'ProfessionalService'],
    '@id'         => rw_abs('#organization'),
    'name'        => BIZ_NAME,
    'legalName'   => BIZ_LEGAL_NAME,
    'url'         => rw_abs(''),
    'logo'        => rw_abs('assets/img/logo.png'),
    'image'       => rw_abs('assets/img/og-default.jpg'),
    'description' => 'Outsourced medical billing, coding, credentialing and revenue cycle management for physician practices across the United States.',
    'slogan'      => BIZ_TAGLINE,
    'telephone'   => BIZ_PHONE,
    'email'       => BIZ_EMAIL,
    'foundingDate' => (string) BIZ_FOUNDED,
    'priceRange'  => '$$',
    'address' => [
        '@type'           => 'PostalAddress',
        'streetAddress'   => BIZ_STREET,
        'addressLocality' => BIZ_CITY,
        'addressRegion'   => BIZ_STATE,
        'postalCode'      => BIZ_ZIP,
        'addressCountry'  => BIZ_COUNTRY,
    ],
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => BIZ_LAT,
        'longitude' => BIZ_LNG,
    ],
    /* 00:00–23:59 across all seven days is how schema.org expresses continuous
       availability, and it is generated from the same constant the visible
       copy uses so the two cannot drift apart. */
    'openingHoursSpecification' => BIZ_HOURS_24_7
        ? [[
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            'opens'     => '00:00',
            'closes'    => '23:59',
        ]]
        : [[
            '@type'     => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
            'opens'     => '08:00',
            'closes'    => '19:00',
        ]],
    'areaServed'  => ['@type' => 'Country', 'name' => 'United States'],
    'sameAs'      => array_values(array_filter([
        SOCIAL_FACEBOOK, SOCIAL_LINKEDIN, SOCIAL_TWITTER, SOCIAL_INSTAGRAM,
    ], fn($u) => $u && $u !== '#')),
    'contactPoint' => [
        '@type'             => 'ContactPoint',
        'telephone'         => BIZ_PHONE,
        'email'             => BIZ_EMAIL,
        'contactType'       => 'sales',
        'availableLanguage' => ['English', 'Spanish'],
        'areaServed'        => 'US',
    ],
];

/* ------------------------------------------------------------- website */
$graph[] = [
    '@type'     => 'WebSite',
    '@id'       => rw_abs('#website'),
    'url'       => rw_abs(''),
    'name'      => BIZ_NAME,
    'publisher' => ['@id' => rw_abs('#organization')],
    'inLanguage' => 'en-US',
];

/* --------------------------------------------------------- breadcrumbs */
if (!empty($breadcrumbs) && count($breadcrumbs) > 1) {
    $items = [];
    foreach ($breadcrumbs as $i => $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $i + 1,
            'name'     => $crumb['label'],
            'item'     => rw_abs($crumb['url'] === 'index.php' ? '' : $crumb['url']),
        ];
    }
    $graph[] = [
        '@type'           => 'BreadcrumbList',
        '@id'             => rw_abs($canonical) . '#breadcrumb',
        'itemListElement' => $items,
    ];
}

/* -------------------------------------------------------------- service */
if (!empty($service_schema)) {
    $graph[] = [
        '@type'       => 'Service',
        '@id'         => rw_abs($canonical) . '#service',
        'name'        => $service_schema['name'],
        'description' => $service_schema['description'],
        'serviceType' => $service_schema['name'],
        'url'         => rw_abs($canonical),
        'provider'    => ['@id' => rw_abs('#organization')],
        'areaServed'  => ['@type' => 'Country', 'name' => 'United States'],
        'audience'    => [
            '@type' => 'Audience',
            'name'  => 'Physician practices, clinics and healthcare providers',
        ],
    ];
}

/* ------------------------------------------------------------------ faq */
if (!empty($faq_schema)) {
    $qs = [];
    foreach ($faq_schema as [$q, $a]) {
        $qs[] = [
            '@type'          => 'Question',
            'name'           => $q,
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $a],
        ];
    }
    $graph[] = [
        '@type'      => 'FAQPage',
        '@id'        => rw_abs($canonical) . '#faq',
        'mainEntity' => $qs,
    ];
}

$jsonld = [
    '@context' => 'https://schema.org',
    '@graph'   => $graph,
];
?>
<script type="application/ld+json">
<?= json_encode($jsonld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>

</script>
