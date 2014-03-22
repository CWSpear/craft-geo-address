<?php
namespace Craft;

class GeoAddress_GeoAddressFieldType extends BaseFieldType
{
    public function getName()
    {
        return Craft::t('Geo Address');
    }

    public function getInputHtml($name, $data)
    {
        if (!$data) {
            $data = $this->prepValue($data);
        }

        craft()->templates->includeCssResource('GeoAddress/css/fieldtype.css');

        $data['fieldName'] = $name;
        return craft()->templates->render('GeoAddress/geoaddress/input', $data);
    }
    
    public function defineContentAttribute()
    {
        return array(
            AttributeType::String,
            'column' => ColumnType::MediumText,
        );
    }

    public function prepValueFromPost($value)
    {
        $addressModel = GeoAddress_GeoAddressModel::populateModel($value);
        $value = $addressModel->getAttributes();

        if (!$addressModel->validate()) {
            // um... how do I handle invalid data in Craft?
        }

        // get Geocode data (should have some error handling here...)
        $geocode = $this->getCoordsByAddress("{$value['street']} {$value['city']}, {$value['state']} {$value['zip']}");
        $value = array_merge($value, $geocode);

        return json_encode($value);
    }

    public function prepValue($value)
    {
        $value = json_decode($value, true);

        if (empty($value)) {
            $addressModel = new GeoAddress_GeoAddressModel;
            $value = $addressModel->getAttributes();
        }

        return $value;
    }

    // I think I should drop this in a service, but... I'm lazy
    private function getCoordsByAddress($address)
    {
        $address = strip_tags($address);
        $geocode = file_get_contents(
            'https://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode($address)
        );

        $geocode = json_decode($geocode);

        if ($geocode->status != 'OK') {
            // better error handling?
            return array(
                'lat' => null,
                'lng' => null,
                'formattedAddress' => null,
            );
        }

        $lat = $geocode->results[0]->geometry->location->lat;
        $lng = $geocode->results[0]->geometry->location->lng;
        $formattedAddress = $geocode->results[0]->formatted_address;

        return array(
            'lat' => $lat,
            'lng' => $lng,
            'formattedAddress' => $formattedAddress,
        );
    }
}
