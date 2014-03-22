<?php
namespace Craft;

class GeoAddress_GeoAddressModel extends BaseModel
{
    protected function defineAttributes()
    {
        $latlngCol = array(
            AttributeType::Number,
            'column'   => ColumnType::Decimal,
            'length'   => 12,
            'decimals' => 8,
        );

        return array(
            'street' => AttributeType::String,
            'city'   => AttributeType::String,
            'state'  => AttributeType::String,
            'zip'    => AttributeType::String,
            'lat'    => $latlngCol,
            'lng'    => $latlngCol,
            'formattedAddress' => AttributeType::String,
        );
    }

    public function rules()
    {
        return array(
            array('street, city, state, zip', 'required'),
        );
    }
}
