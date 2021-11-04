<?php
class Elks extends Module
{
    public function __construct()
    {
        $this->name = "elks";
        $this->tab = "front_office_features";
        $this->version = "1.0.0";
        $this->author = "Zoltán Dávid";
        $this->need_instance = 0;
        $this->secure_key = Tools::encrypt($this->name);
        $this->bootstrap = true;
        $this->displayName = $this->l('46elks Demo Module');
        $this->description = $this->l('This is a Demo Module for the 46elks features');
        parent::__construct();
    }
    public function install()
    {
        if (parent::install() == false) {
            return false;
        }

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'ElksAdmin';
        $tab->position = 3;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = '46elks Admin Page';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;
        $tab->add();
        $tab->save();
        return true;
    }
    public function uninstall()
    {
        if (parent::uninstall() == false) {
            return false;
        }
        return true;
    }
}
