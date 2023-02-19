import EasyMDE from 'easymde';

import * as FilePond from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImageValidateSize from 'filepond-plugin-image-validate-size';

import Alpine from 'alpinejs';

/**
 * EasyMDE
 */
window.EasyMDE = EasyMDE;

/**
 * FilePond
 */
window.FilePond = FilePond;
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;
window.FilePondPluginImageValidateSize = FilePondPluginImageValidateSize;

/**
 * Alpine.js
 */
window.Alpine = Alpine;
Alpine.start();
