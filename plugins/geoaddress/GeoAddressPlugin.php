<?php
namespace Craft;

class GeoAddressPlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('Geo Address');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Mavrx';
    }

    public function getDeveloperUrl()
    {
        return 'http://mavrx.io';
    }

    public function hasCpSection()
    {
        return false;
    }
}
