<?php
require_once 'service/v4_1/registry.php';
class registry_v4_1_SticCustom extends registry_v4_1
{
    protected function registerFunction()
    {

        parent::registerFunction();
        $this->serviceClass->registerFunction(
            'set_image',
            array(
                'session' => 'xsd:string',
                'image_data' => 'tns:new_image_file'),
            array(
                'return' => 'xsd:boolean')
        );

        $this->serviceClass->registerFunction(
            'get_image',
            array(
                'session' => 'xsd:string',
                'image_data' => 'tns:image_file'),
            array(
                'return' => 'tns:image_data')
        );

        // Rebuid SinergiaDA views API function
        $this->serviceClass->registerFunction(
            'rebuild_sda',
            array(
                'session' => 'xsd:string',
            ),
            array(
                'return' => 'xsd:string')
        );
    }

    protected function registerTypes()
    {
        parent::registerTypes();

        $this->serviceClass->registerType(
            'new_image_file',
            'complexType',
            'struct',
            'all',
            '',
            array(
                "id" => array('name' => "id", 'type' => 'xsd:string'),
                "module" => array('name' => "id", 'type' => 'xsd:string'),
                "field" => array('name' => "id", 'type' => 'xsd:string'),
                "filename" => array('name' => "filename", 'type' => 'xsd:string'),
                "file" => array('name' => "file", 'type' => 'xsd:string'),
            )
        );

        $this->serviceClass->registerType(
            'image_file',
            'complexType',
            'struct',
            'all',
            '',
            array(
                "id" => array('name' => "id", 'type' => 'xsd:string'),
                "field" => array('name' => "id", 'type' => 'xsd:string'),
            )
        );

        $this->serviceClass->registerType(
            'image_data',
            'complexType',
            'struct',
            'all',
            '',
            array(
                "mime_type" => array('name' => "id", 'type' => 'xsd:string'),
                "data" => array('name' => "id", 'type' => 'xsd:string'),
            )
        );
    }
}
