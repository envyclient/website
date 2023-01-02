import EasyMDE from 'easymde';
import * as FilePond from 'filepond';
import Alpine from 'alpinejs';

/**
 * EasyMDE
 */
window.EasyMDE = EasyMDE;

/**
 * FilePond
 */
window.FilePond = FilePond;
window.FilePondPluginFileValidateSize = require('filepond-plugin-file-validate-size');
window.FilePondPluginFileValidateType = require('filepond-plugin-file-validate-type');
window.FilePondPluginImageValidateSize = require('filepond-plugin-image-validate-size');

/**
 * Alpine.js
 */
window.Alpine = Alpine;
Alpine.start();
