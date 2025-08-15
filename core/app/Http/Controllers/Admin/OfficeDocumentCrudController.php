<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfficeDocumentRequest;
use App\Models\OfficeFile;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OfficeDocumentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OfficeDocumentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\OfficeDocument::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/office-document');
        CRUD::setEntityNameStrings('office document', 'office documents');
        $this->crud->entity_name_plural="فایل های دفاتر";
        $this->crud->entity_name="فایل دفتر";

        // Handle office_code parameter from URL
        if (request()->has('office_code')) {
            $officeCode = request()->get('office_code');
            CRUD::addClause('where', 'office_code', $officeCode);

            // Update page title to show filtered results
            $this->crud->setHeading('بازرسی های قدیم - دفتر ' . $officeCode);
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Add back button if office_code is provided
        if (request()->has('office_code')) {
            $officeCode = request()->get('office_code');
            CRUD::addButton('top', 'back_to_office_files', 'view', 'crud::buttons.back_to_office_files', 'beginning');
        }

        CRUD::addColumns([
            [
                'name'  => 'office_code',
                'label' => 'کد دفتر',
                'type'  => 'text',
            ],
            [
                'name'  => 'inspection_date',
                'label' => 'تاریخ بازرسی',
                'type'  => 'persiandate',
            ],
            [
                'name'  => 'files',
                'label' => 'فایل ها',
                'type'  => 'array',
            ],
            [
                'name'  => 'created_at',
                'label' => 'تاریخ ایجاد',
                'type'  => 'persiandatetime',
            ],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(OfficeDocumentRequest::class);

        // Get office codes from OfficeFile model
        $officeCodes = OfficeFile::all()->pluck('office_code', 'office_code')->toArray();

        CRUD::addFields([
            [
                'name'  => 'user_id',
                'label' => 'کاربر',
                'type'  => 'hidden',
                'value' => auth('backpack')->id(),
            ],

            [
                'name'        => 'office_code',
                'label'       => 'کد دفتر',
                'type'        => 'select2_from_array',
                'options'     => $officeCodes,
                'allows_null' => false,
            ],
            [
                'name'  => 'inspection_date',
                'label' => 'تاریخ بازرسی',
                'type'  => 'persian_datepicker',
                'date_picker_options' => [
                    'todayBtn' => 'linked',
                    'format'   => 'dd-mm-yyyy',
                    'language' => 'fa'
                ],
            ],

            [
                'name'  => 'name',
                'label' => 'نام فایل (ها)',
                'type'  => 'text',
            ],
            [
                'name'  => 'files',
                'label' => 'فایل ها',
                'type'  => 'browse_multiple',
                'disk'  => 'public',
            ],
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Show operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-show
     * @return void
     */
    protected function setupShowOperation()
    {
        CRUD::addColumns([
            [
                'name'  => 'office_code',
                'label' => 'کد دفتر',
                'type'  => 'text',
            ],
            [
                'name'  => 'inspection_date',
                'label' => 'تاریخ بازرسی',
                'type'  => 'persiandate',
            ],
            [
                'name'  => 'name',
                'label' => 'نام فایل (ها)',
                'type'  => 'text',
            ],
            [
                'name'  => 'created_at',
                'label' => 'تاریخ ایجاد',
                'type'  => 'persiandatetime',
            ],
        ]);

        // Add custom lightbox field for files
        CRUD::addColumn([
            'name'  => 'files',
            'label' => 'فایل ها',
            'type'  => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->files || empty($entry->files)) {
                    return '<span class="text-muted">هیچ فایلی آپلود نشده است</span>';
                }

                $html = '<div class="row">';
                foreach ($entry->files as $file) {
                    $fileUrl = asset( $file);
                    $fileName = basename($file);
                    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    // Check if it's an image
                    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp','PNG'];
                    $isImage = in_array($fileExtension, $imageExtensions);

                    if ($isImage) {
                        $html .= '<div class="col-md-3 col-sm-4 col-6 mb-3">';
                        $html .= '<div class="card h-100">';
                        $html .= '<div class="card-body p-2 text-center">';
                        $html .= '<a href="' . $fileUrl . '" data-lightbox="office-files" data-title="' . $fileName . '">';
                        $html .= '<img src="' . $fileUrl . '" class="img-fluid rounded" style="max-height: 150px; object-fit: cover;" alt="' . $fileName . '">';
                        $html .= '</a>';
                        $html .= '<div class="mt-2">';
                        $html .= '<small class="text-muted d-block">' . $fileName . '</small>';
                        $html .= '<a href="' . $fileUrl . '" class="btn btn-sm btn-primary mt-1" target="_blank">';
                        $html .= '<i class="la la-download"></i> دانلود';
                        $html .= '</a>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    } else {
                        // Get appropriate icon based on file type
                        $icon = $this->getFileIcon($fileExtension);
                        $iconColor = $this->getFileIconColor($fileExtension);

                        $html .= '<div class="col-md-3 col-sm-4 col-6 mb-3">';
                        $html .= '<div class="card h-100">';
                        $html .= '<div class="card-body p-2 text-center">';
                        $html .= '<div class="mb-2">';
                        $html .= '<i class="' . $icon . '" style="font-size: 3rem; color: ' . $iconColor . ';"></i>';
                        $html .= '</div>';
                        $html .= '<div class="mt-2">';
                        $html .= '<small class="text-muted d-block">' . $fileName . '</small>';
                        $html .= '<small class="text-info d-block">' . strtoupper($fileExtension) . '</small>';
                        $html .= '<a href="' . $fileUrl . '" download="' . $fileName . '" class="btn btn-sm btn-primary mt-1" target="_blank">';
                        $html .= '<i class="la la-download"></i> دانلود';
                        $html .= '</a>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                        $html .= '</div>';
                    }
                }
                $html .= '</div>';

                return $html;
            }
        ]);


    }

    /**
     * Get file icon based on file extension.
     *
     * @param string $extension
     * @return string
     */
    private function getFileIcon($extension)
    {
        switch ($extension) {
            case 'pdf':
                return 'la la-file-pdf-o';
            case 'doc':
            case 'docx':
                return 'la la-file-word-o';
            case 'xls':
            case 'xlsx':
                return 'la la-file-excel-o';
            case 'zip':
            case 'rar':
                return 'la la-file-archive-o';
            case 'txt':
                return 'la la-file-text-o';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                return 'la la-file-image-o';
            default:
                return 'la la-file-o';
        }
    }

    /**
     * Get file icon color based on file extension.
     *
     * @param string $extension
     * @return string
     */
    private function getFileIconColor($extension)
    {
        switch ($extension) {
            case 'pdf':
                return '#f0ad4e'; // Orange
            case 'doc':
            case 'docx':
                return '#5cb85c'; // Green
            case 'xls':
            case 'xlsx':
                return '#428bca'; // Blue
            case 'zip':
            case 'rar':
                return '#d9534f'; // Red
            case 'txt':
                return '#5bc0de'; // Teal
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'bmp':
            case 'webp':
                return '#5bc0de'; // Teal
            default:
                return '#6c757d'; // Gray
        }
    }
}
