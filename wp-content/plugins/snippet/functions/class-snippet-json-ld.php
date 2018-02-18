<?php

// =============================================================================
// FUNCTIONS/CLASS-SNIPPET-JSON-LD.PHP
// -----------------------------------------------------------------------------
// This generates schemas for all data.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Class Setup
// =============================================================================

// Class Setup
// =============================================================================

class Tco_Snippet_Json_Ld {

  private $post_types = array( 'post', 'page' );

  private $schemas = array();

  private $prefix  = '_snippet';

  function __construct() {

    $schemas = require( SNIPPET_PATH . '/functions/includes/schema-metaboxes-list.php' );
    foreach ( $schemas as $key => $schema ) {
      $this->schemas[ $key ] = array (
        'id'        => $this->prefix . '_' . $key,
        'label'     => $schema,
        'metaboxes' => require( SNIPPET_PATH . "/functions/includes/schema-metaboxes-{$key}.php" ),
      );
    }

  }

  //
  // Generate Website schema
  //

  function generate_website( $data ) {

    if ( ! array_key_exists('website_url', $data)  ) {
      return null;
    }

    if ( empty($data['website_url']) ) {
      return null;
    }

    $json_ld = array(
      '@context' => 'http://schema.org/',
      '@type'    => 'WebSite',
      'url'      => strip_tags( $data['website_url'] ),
    );

    if ( array_key_exists('website_name', $data) && ! empty($data['website_name']) ) {
      $json_ld['name'] = strip_tags( $data['website_name'] );
    }

    if ( array_key_exists('website_alternate_name', $data) && ! empty($data['website_alternate_name']) ) {
      $json_ld['alternateName'] = strip_tags( $data['website_alternate_name'] );
    }

    if ( array_key_exists('website_sitelinks', $data) && $data['website_sitelinks'] === 'yes' ) {
      $json_ld['potentialAction'] = array(
          '@type'       => 'SearchAction',
          'target'      => esc_attr ( get_option('siteurl') . '/?s={query}' ),
          'query-input' => 'required name=query',
      );
    }

    return (object) $json_ld;
  }


  //
  // Generate Organization schema
  //

  function generate_organization( $data ) {


    // Organization

    if ( ! array_key_exists('organization_type', $data) || ! array_key_exists('organization_name', $data)) {
      return null;
    }

    if ( empty($data['organization_type']) || empty($data['organization_name']) ) {
      return null;
    }

    if ( empty($data['organization_url']) || empty($data['organization_url']) ) {
      return null;
    }

    if ( empty($data['organization_image']) || empty($data['organization_image']) ) {
      return null;
    }

    $json_ld = array(
      '@context' => 'http://schema.org/',
      '@type'    => strip_tags( $data['organization_type'] ),
      'name'     => strip_tags( $data['organization_name'] ),
      'url'      => strip_tags( $data['organization_url'] ),
    );

    if ( array_key_exists('organization_image', $data) && ! empty($data['organization_image']) ) {
      $value = @json_decode( $data['organization_image'] );
      $json_ld[ 'image' ] = array(
        '@type'  => 'ImageObject',
        'url'    => strip_tags( is_object( $value ) ? $value->url : $data['organization_image'] ),
        'height' => is_object( $value ) ? strip_tags( $value->height ) : null,
        'width'  => is_object( $value ) ? strip_tags( $value->width ) : null,
      );
    }

    if ( array_key_exists('organization_logo', $data) && ! empty($data['organization_logo']) ) {
      $value = @json_decode( $data['organization_logo'] );
      $json_ld[ 'logo' ] = array(
        '@type'  => 'ImageObject',
        'url'    => strip_tags( is_object( $value ) ? $value->url : $data['organization_logo'] ),
        'height' => is_object( $value ) ? strip_tags( $value->height ) : null,
        'width'  => is_object( $value ) ? strip_tags( $value->width ) : null,
      );
    }

    if ( array_key_exists('organization_description', $data) && ! empty($data['organization_description']) ) {
      $json_ld['description'] = strip_tags( $data['organization_description'] );
    }

    $excludeOH = array(
      'Organization', 'Airline', 'Corporation',
      'EducationalOrganization', 'CollegeOrUniversity', 'ElementarySchool', 'HighSchool', 'MiddleSchool', 'Preschool', 'School',
      'GovernmentOrganization',
      'MedicalOrganization', 'DiagnosticLab', 'VeterinaryCare',
      'NGO',
      'PerformingGroup', 'DanceGroup', 'MusicGroup', 'TheaterGroup',
      'SportsOrganization', 'WorkersUnion'
    );

    if ( !in_array($data['organization_type'], $excludeOH) && array_key_exists('organization_operation_hours', $data) && ! empty($data['organization_operation_hours']) ) {
      $json_ld['openingHours'] = explode( "|", strip_tags( $data['organization_operation_hours'] ) );
    }

    if ( array_key_exists('organization_additional_type', $data) && ! empty($data['organization_additional_type']) ) {
      $json_ld['additionalType'] = explode( "\r\n", strip_tags( $data['organization_additional_type'] ) );
    }

    if ( array_key_exists('organization_price_range', $data) && ! empty($data['organization_price_range']) ) {
      $json_ld['priceRange'] = strip_tags( $data['organization_price_range'] );
    }

    // Place

    $place = $this->generate_place( $data );
    if ( ! empty($place) ) {
      $json_ld['location'] = $place;
      $json_ld['address'] = $place['address'];
    }

    // Social

    $social = $this->generate_social( $data );
    if ( ! empty($json_ld) ) {
      $json_ld['sameAs'] = $social;
    }

    // Contact

    $json_ld['contactPoint'] = $this->generate_contact( $data );

    if (count($json_ld['contactPoint']) > 0 ) {
      $json_ld['telephone'] = $json_ld['contactPoint'][0]['telephone'];
    }

    // Return

    return (object) $json_ld;
  }


  //
  // Generate Place schema
  //

  function generate_place( $data ) {

    $json_ld = array();

    $address_fields = array(
      'address_country',
      'address_region',
      'address_locality',
      'address_street_address',
      'address_postal_code',
    );
    $address_ok = true;

    foreach( $address_fields as $af ) {
      if ( ! array_key_exists($af, $data) ) {
        $address_ok = false;
      } else if ( empty($data[$af]) ) {
        $address_ok = false;
      }
    }

    $json_ld['@type'] = 'Place';
    if ( array_key_exists( 'name', $data ) ) {
      $json_ld['name']  = strip_tags( $data['name'] );
    }

    if ($address_ok) {
      $json_ld['address'] = array(
        '@type'           => 'PostalAddress',
        'addressCountry'  => strip_tags( $data['address_country'] ),
        'addressLocality' => strip_tags( $data['address_locality'] ),
        'addressRegion'   => strip_tags( $data['address_region'] ),
        'streetAddress'   => strip_tags( $data['address_street_address'] ),
        'postalCode'      => strip_tags( $data['address_postal_code'] ),
      );
    }

    // GeoCoordinates

    $geo = $this->generate_geo( $data );
    if ( ! empty($geo) ) {
      $json_ld['geo'] = $geo;
    }

    return $json_ld;
  }


  //
  // Generate GeoCoordinates schema
  //

  function generate_geo( $data ) {

    $json_ld = array();

    if ( array_key_exists('geo_latitude', $data) && array_key_exists('geo_longitude', $data)
      && ! empty($data['geo_latitude']) && ! empty($data['geo_longitude'])
    ) {
      $json_ld = array(
        '@type'     => 'GeoCoordinates',
        'longitude' => strip_tags( $data['geo_latitude'] ),
        'latitude'  => strip_tags( $data['geo_longitude'] ),
      );
    }

    return $json_ld;

  }


  //
  // Generate Social schema
  //

  function generate_social( $data ) {

    $social_fields = array(
      'social_facebook',
      'social_twitter',
      'social_google_plus',
      'social_instagram',
      'social_youtube',
      'social_linkedin',
      'social_myspace',
      'social_pinterest',
      'social_sondcloud',
      'social_tumblr',
    );

    $json_ld = array();

    foreach( $social_fields as $sf ) {
      if ( array_key_exists($sf, $data) && ! empty($data[$sf]) ) {
        $json_ld[] = strip_tags( $data[$sf] );
      }
    }

    return $json_ld;

  }


  //
  // Generate Contact schema
  //

  function generate_contact( $data ) {

    $json_ld = array();
    $item = array();

    foreach ( $data['contacts'] as $key => $contact ) {
      if ( array_key_exists( 'telephone', $contact ) && array_key_exists( 'type', $contact )
        && ! empty( $contact['telephone']) && ! empty($contact['type'] )
      ) {
        $item = array(
          '@context'    => 'http://schema.org/',
          '@type'       => 'ContactPoint',
          'telephone'   => strip_tags( $contact['telephone'] ),
          'contactType' => strip_tags( $contact['type'] ),
        );

        if ( array_key_exists( 'option', $contact ) && ! empty( $contact['option'] ) ) {
          $item['contactOption'] = strip_tags( $contact['option'] );
        }

        if ( array_key_exists( 'area_served', $contact ) && ! empty( $contact['areaServed'] ) ) {
          $item['areaServed'] = strip_tags( $contact['area_served'] );
        }

        if ( array_key_exists( 'availableLanguage', $contact ) && ! empty( $contact['availableLanguage'] ) ) {
          $item['availableLanguage'] = strip_tags( $contact['availableLanguage'] );
        }

        if ( array_key_exists( 'hoursAvailable', $contact ) && ! empty( $contact['hoursAvailable'] ) ) {
          $item['hoursAvailable'] = explode( "|", strip_tags( $contact['hoursAvailable'] ) );
        }
        $json_ld[] = $item;
      }
    }

    return $json_ld;
  }

  //
  // Generate schema for Page/Post
  //

  function generate_schema_for_page_post( $post_id ) {

    $json_ld = null;

    $data = get_post_meta( $post_id, $this->prefix, true );

    if ( ! is_array( $data ) ) {
      return null;
    }

    if ( ! array_key_exists( 'post_type_schema', $data ) ) {
      return null;
    }

    if ( ! array_key_exists($data['post_type_schema'], $data) ) {
      return null;
    }

    $schema_key = $data['post_type_schema'];

    if ( array_key_exists( $schema_key, $this->schemas) ) {
      $schema = $this->schemas[ $schema_key ];
      $json_ld = array(
        '@context' => 'http://schema.org/',
        '@type' => strip_tags( $schema['label'] ),
      );
      foreach ( $schema['metaboxes'] as $metabox) {
        $metabox_value = array_key_exists( $metabox['name'], $data[ $schema_key ] )
                       ? $data[ $schema_key ][ $metabox['name'] ]
                       : null;

        if ( ! empty ($metabox_value) ) {

          switch ( $metabox['type'] ) {

            case 'type':
            case 'localbusiness':
              $json_ld['@type'] = strip_tags( str_replace(' ', '', $metabox_value) );
              break;

            case 'rating':
              $json_ld[ $metabox['name'] ] = array(
                '@type' => 'Rating',
                'ratingValue' => strip_tags( $metabox_value ),
                'worstRating' => 1,
                'bestRating'  => 5,
              );
              break;

            case 'datetime':
               if ( ! empty( $metabox_value['date'] ) ) {
                 $json_ld[ $metabox['name'] ] = strip_tags( $metabox_value['date'] );
                 if ( ! empty( $metabox_value['time'] ) ) {
                   $json_ld[ $metabox['name'] ] .= ' ' . strip_tags( $metabox_value['time'] );
                 }
               }
              break;

            // @todo hour availability
            case 'houravailability':
              break;

            case 'place':
            case 'postaladdress':
              if ( $metabox['type'] === 'place' ) {
                $json_ld[ $metabox['name'] ]['@type'] = 'Place';
                $json_ld[ $metabox['name'] ]['name']  = strip_tags( $metabox_value['name'] );
              }
              $address = array();
              foreach ( $metabox_value['postaladdress'] as $k => $v) {
                if ( ! empty( $v ) ) {
                  $address[ $k ] = strip_tags( $v );
                }
              }
              if ( ! empty ( $address )) {
                if ( $metabox['type'] === 'place' ) {
                  $json_ld[ $metabox['name'] ]['address'] = $address;
                }
                if ( $metabox['type'] === 'postaladdress' ) {
                  $json_ld[ $metabox['name'] ] = array(
                    '@type' => 'PostalAddress'
                  );
                  $json_ld[ $metabox['name'] ] = array_merge( $json_ld[ $metabox['name'] ], $address );
                }
              }
              if ( array_key_exists('geo', $metabox_value) ) {
                if ( array_key_exists('latitude', $metabox_value['geo']) && array_key_exists('longitude', $metabox_value['geo'])
                  && ! empty($metabox_value['geo']['latitude']) && ! empty($metabox_value['geo']['longitude'])
                ) {
                  $json_ld[ $metabox['name'] ]['geo'] = array(
                    '@type'     => 'GeoCoordinates',
                    'longitude' => strip_tags( $metabox_value['geo']['latitude'] ),
                    'latitude'  => strip_tags( $metabox_value['geo']['longitude'] ),
                  );
                }

              }
              break;

            case 'offer':
              $json_ld[ $metabox['name'] ]['@type'] = 'Offer';
              foreach ( $metabox_value as $k => $v) {
                if ( ! empty( $v ) ) {
                  if ( $k == 'validFrom') {
                    $json_ld[ $metabox['name'] ][ $k ] = strip_tags( $v['date'] ) . ' ' . strip_tags( $v['time'] );
                  } else {
                    $json_ld[ $metabox['name'] ][ $k ] = strip_tags( $v );
                  }
                }
              }
              break;
            case 'media':
              $value = @json_decode( $metabox_value );
              $media = array(
                '@type'  => 'ImageObject',
                'url'    => strip_tags( is_object( $value ) ? $value->url : $metabox_value ),
                'height' => is_object( $value ) ? strip_tags( $value->height ) : null,
                'width'  => is_object( $value ) ? strip_tags( $value->width ) : null,
              );

              if ( in_array( $metabox['id'], array('_snippet_article_publisher_logo', '_snippet_blogposting_publisher_logo', '_snippet_newsarticle_publisher_logo') ) ) {
                $json_ld[ 'publisher' ][ $metabox['name'] ] = $media;
              } else {
                $json_ld[ $metabox['name'] ] = $media;
              }

              break;
            default:
            case 'currency':
            case 'availability':
            case 'item-condition':
            case 'textarea':
            case 'integer':
            case 'money':
            case 'text':

              $expanded_schemas = array('Thing', 'Person', 'Organization');
              if ( in_array( $metabox['schema_type'], $expanded_schemas ) ) {
                $json_ld[ $metabox['name'] ] = array(
                  '@type' => strip_tags( $metabox['schema_type'] ),
                  'name'  => strip_tags( $metabox_value )
                );
              } else {
                $json_ld[ $metabox['name'] ] = strip_tags( $metabox_value );
              }

              break;
          }

        }
      }
    }

    return $json_ld;
  }

  //
  // Generate
  //

  function generate_person() {
    $author = get_the_author();
  }


}
