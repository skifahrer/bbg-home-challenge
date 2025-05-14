import { Application } from '@hotwired/stimulus';
import "bootstrap"
import "bootstrap/dist/css/bootstrap.min.css"

// import controllers
import catalog_controller from "../../../stimulus/catalog_controller";

// rund sitmulus app
window.Stimulus = Application.start();

// registre controllers
Stimulus.register('catalog', catalog_controller);
