<?php
return array(
	'bjyauthorize' => array(

		// Using the authentication identity provider, which basically reads the roles from the auth service's identity
		'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

		'role_providers'        => array(
			// using an object repository (entity repository) to load all roles into our ACL
			'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
				'object_manager'    => 'doctrine.entitymanager.orm_default',
				'role_entity_class' => 'Application\Entity\Base\Role',
			),
		),

        // Resources
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'setting' => array(),
                'navigation' => array(),
                'user'    => array(),
                'office'  => array(),
                'adjuster' => array(),
                'company' => array(),
                'agent' => array(),
                'loss' => array(),
                'settlement' => array(),
                'claim' => array(),
                'claimAll' => array(),
                'claimOffice' => array(),
                'claimActivity' => array(),
                'claimManagerActivity' => array(),
                'claimPhoto' => array(),
                'claimPhotoSheet' => array(),
                'claimAttachment' => array(),
                'claimReport' => array(),
                'claimDateAssigned' => array(),
                'claimDiaryDate' => array(),
                'claimSupervisorDiaryDate' => array(),
                'invoice' => array(),
                'reportInvoice' => array(),
                'reportAccess' => array(),
                'dashboardReports' => array(),
                'officeSelector' => array(),
            ),
        ),
        // These are used for fine-grain explicit ACL control (i.e. in views, in controllers, etc)
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'officeSelector', array('view')), // Settings navigation tab (main)
                    array(array('manager', 'admin', 'super'), 'officeSelector', array('change')), // Settings navigation tab (main)

                    // MAIN NAVIGATION PERMISSIONS
                    array(array('super'), 'setting', array('index')), // Settings navigation tab (main)
                    array(array('admin','super'), 'navigation', array('setting')), // Settings navigation tab (main)
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'navigation', array('claim', 'accounting')), // Claim, report, accounting tabs in navigation
                    array(array('manager', 'admin', 'super'), 'navigation', array('report',)),

                    // Basic CRUDs
                    // As of 9/30/2014, super can do it all, nobody else can do a thing
                    array(array('super'), 'user', array('view', 'edit', 'add', 'disable', 'impersonate')), // User Permissions
                    array(array('admin','super'), 'office', array('view')),
                    array(array('super'), 'office', array('edit', 'add')),
                    array(array('admin','super'), 'adjuster', array('view')),
                    array(array('super'), 'adjuster', array('view', 'edit', 'add')),
                    array(array('admin','super'), 'company', array('view')),
                    array(array('super'), 'company', array('view', 'edit', 'add')),
                    array(array('admin','super'), 'agent', array('view',)),
                    array(array('super'), 'agent', array('view', 'edit', 'add', 'manageCompany')),
                    array(array('admin','super'), 'loss', array('view')),
                    array(array('super'), 'loss', array('view', 'edit', 'add')),
                    array(array('admin','super'), 'settlement', array('view')),
                    array(array('super'), 'settlement', array('view', 'edit', 'add')),

                    // CLAIM (PRIMARY) PERMISSIONS
                    array(array('manager', 'admin', 'super'), 'claim', array('create', 'add', 'edit')), // ClaimCanCreate, ClaimCanEdit
                    array(array('manager', 'admin', 'super'), 'claim', array('associateAgency')), // ClaimCanAssociateAgency
                    array(array('manager', 'admin', 'super'), 'claim', array('searchSupervisorDiaryDate')), // Can see supervisordiarydate in claim search
                    array(array('manager', 'admin', 'super'), 'claim', array('close')), // ClaimCanClose
                    array(array('super'), 'claim', array('delete')), // ClaimCanDelete
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claim', array('viewAll')), //AllClaimsCanView
                    array(array('adjuster','general_adjuster', 'manager', 'admin', 'super'), 'claim', array('viewAllForOffice')),  //OfficeClaimsCanView
                    array(array('manager', 'admin', 'super'), 'claim', array('editAll')), // All ClaimsCanEdit
                    array(array('general_adjuster', 'manager', 'admin', 'super'), 'claim', array('editAllForOffice')), //OfficeClaimsCanEdit

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimAll', array('view')),
                    array(array('general_adjuster', 'manager', 'admin', 'super'), 'claimAll', array('edit')),

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimOffice', array('view')),
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimOffice', array('edit')),

                    // CLAIM ACTIVITY
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimActivity', array('add', 'edit', 'view')), //ClaimActivityCanAdd, ClaimActivityCanEdit, ClaimActivityCanView
                    array(array('manager', 'admin', 'super'), 'claimActivity', array('addAll', 'editAll','viewAll')), //ClaimActivityCanAddAll, ClaimActivityCanEditAll, ClaimActivityCanViewAll
                    array(array('general_adjuster', 'manager', 'admin', 'super'), 'claimActivity', array('addAllForOffice', 'editAllForOffice','viewAllForOffice')), //ClaimActivityCanAddAllForOffice, ClaimActivityCanEditAllForOffice, ClaimActivityCanViewAllForOffice
                    // I can mark activity and view activity MangerOnly [claimManagerActivityCanUse]
                    array(array('manager', 'admin', 'super'), 'claimActivity', array('managerOnly')), //ClaimManagerActivityCanUse
                    array(array('manager', 'admin', 'super'), 'claimActivity', array('viewAdminOnly', 'setViewAdminOnly')), //ClaimManagerActivityCanUse

                    // CLAIM PHOTO
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimPhoto', array('add' ,'edit', 'view')), // ClaimPhotoCanAdd, ClaimPhotoCanEdit, ClaimPhotoCanView
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimPhoto', array('delete')), // ClaimPhotoCanDelete
                    array(array('manager', 'admin', 'super'), 'claimPhoto', array('addAll' ,'editAll', 'deleteAll', 'viewAll')), // ClaimPhotoCanAddAll, ClaimPhotoCanEditAll, ClaimPhotoCanViewAll, ClaimPhotoCanDeleteAll
                    array(array('general_adjuster', 'manager', 'admin', 'super'), 'claimPhoto', array('addAllForOffice' ,'editAllForOffice', 'deleteAllForOffice', 'viewAllForOffice')), // ClaimPhotoCanAddAllForOffice, ClaimPhotoCanEditAllForOffice, ClaimPhotoCanViewAllForOffice, ClaimPhotoCanDeleteAllForOffice

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimPhotoSheet', array('create')), //ClaimPhotoSheetCanCreate

                    // CLAIM ATTACHMENT
                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimAttachment', array('add', 'edit', 'view')), // ClaimAttachmentCanAdd, ClaimAttachmentCanEdit, ClaimAttachmentCanView
                    array(array('manager', 'admin', 'super'), 'claimAttachment', array('delete')), // ClaimAttachmentCanAdd, ClaimAttachmentCanEdit, ClaimAttachmentCanView
                    array(array('manager', 'admin', 'super'), 'claimAttachment', array('addAll', 'viewAll', 'editAll', 'deleteAll')), //ClaimAttachmentCanAddAll, ClaimAttachmentCanEditAll, ClaimAttachmentCanDeleteAll, ClaimAttachmentCanViewAll
                    array(array('general_adjuster', 'manager', 'admin', 'super'), 'claimAttachment', array('addAllForOffice', 'viewAllForOffice', 'editAllForOffice')), //ClaimAttachmentCanAddAllForOffice, ClaimAttachmentCanEditAllForOffice, ClaimAttachmentCanDeleteAllForOffice, ClaimAttachmentCanViewAllForOffice

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'claimReport', array('create', 'edit', 'view')), // ClaimReportCanCreate, ClaimReportCanEdit, ClaimReportCanView

                    array(array('super'), 'claimDateAssigned', array('edit')), // ClaimDateAssignedCanEdit

                    array(array('manager', 'admin', 'super'), 'claimDiaryDate', array('edit')), // ClaimDiaryDateCanEdit

                    array(array('manager', 'admin', 'super'), 'claimSupervisorDiaryDate', array('edit')), // ClaimSupervisorDiaryDateCanEdit

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'invoice', array('view')), // InoviceCanView
                    array(array('admin', 'super'), 'invoice', array('add', 'edit')), //InvoiceCanAdd
                    array(array('super'), 'invoice', array('editStatus')), // InvoiceCanEditStatus
                    array(array('super'), 'invoice', array('markAsPaid')), // InvoiceCanMarkPaid
                    array(array('admin', 'super'), 'invoice', array('export')), // InvoiceCanExport


                    array(array('super'), 'reportInvoice', array('byAdjuster')), // ReportsInvoicesByAdjuster
                    array(array('super'), 'reportInvoice', array('byOffice')), // ReportsInvoicesByOffice
                    array(array('super'), 'reportInvoice', array('byCompany')), // ReportsInvoicesByCompany

                    array(array('adjuster', 'general_adjuster', 'manager', 'admin', 'super'), 'reportAccess', array('simple')),
                    array(array('manager', 'admin', 'super'), 'reportAccess', array('full')),

                    array(array('manager', 'admin', 'super'), 'dashboardReports', array('recent')),
                ),
            ),
        ),

        /*
         * Per conversation 8/14/14 with Jon K, permissions need to be stored in the database.
         *
         * These links detail how to accomplish this ....
         *
         * http://stackoverflow.com/questions/18264671/zf2-bjyauthorize-get-rules-and-guards-from-database-as-roles-are-already-get
         * http://stackoverflow.com/questions/17082907/bjyauthorize-settings-file-connect-with-database-table
         *
         *
         * Per conversation 8/15/14 with Jon K, permissions do not need to be stored in the database at this time, static file is fine
         *
         *
         * These controller guards provide high-level, broad range ACL (i.e. only super admins should be able to use
         * Controller X's action Y.
         *
         */
		'guards' => array(
			'BjyAuthorize\Guard\Controller' => array(
				// Let everyone login & logout
				array('controller' => 'zfcuser', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
				// Index / Home Page (Everyone can see for now)
				//array('controller' => 'Application\Controller\Index', 'action' => 'index', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),


                /*
                 *  MAIN - SETTINGS
                 *
                 */
                array('controller' => 'Application\Controller\Setting', 'action' => 'index', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'profile', 'roles' => array('user','adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'editUser', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'impersonate', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'authorizedUsers', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'typeOfLoss', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'agents', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'insuranceCompanies', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'adjusters', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Setting', 'action' => 'index', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),


                /*
                 *  MAIN -INDEX
                 *
                 */
                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),


                /*
                 * MAIN - CLAIM
                 *
                 */
                array('controller' => 'Application\Controller\Claim',  'action' => 'new', 'roles' => array('manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'edit', 'roles' => array('general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'index', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'photos', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'attachments', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'invoices', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Claim',  'action' => 'advanced', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 * MAIN - CLAIM ACTIVITIES
                 *
                 */
                array('controller' => 'Application\Controller\Activity',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

				/*
				 * MAIN - CLAIM INVOICES
				 *
				 */
				array('controller' => 'Application\Controller\Invoice',  'action' => 'index', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Invoice',  'action' => 'customer', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Invoice',  'action' => 'accounting', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Invoice',  'action' => 'view', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Invoice',  'action' => 'new', 'roles' => array('admin', 'super')),
                array('controller' => 'Application\Controller\Invoice',  'action' => 'edit', 'roles' => array('admin', 'super')),

				/*
				 * MAIN - CLAIM LONG REPORT
				 *
				 */
				array('controller' => 'Application\Controller\Report\Long',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

				/*
				 * MAIN - CLAIM LIABILITY REPORT
				 *
				 */
				array('controller' => 'Application\Controller\Report\Liability',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
				 * MAIN - CLAIM SHORT REPORT
				 *
				 */
                array('controller' => 'Application\Controller\Report\Short',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

				/*
				 * MAIN - CLAIM PROPERTY DAMAGE REPORT
				 *
				 */
				array('controller' => 'Application\Controller\Report\PropertyDamage',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                /*
                 *  MAIN - GENERATED REPORT
                 *
                 */
                array('controller' => 'Application\Controller\GeneratedReport', 'roles' => array('manager', 'admin', 'super')),
                /*
                 *  MAIN - CLAIM GENERATED REPORT
                 *
                 */
                array('controller' => 'Application\Controller\ClaimGeneratedReport',  'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  MAIN -ACCOUNTING
                 *
                 */
                array('controller' => 'Application\Controller\Accounting', 'action' => array(
                    'index',
                    'create-invoice',
                    'modify-invoice',
                ),  'roles' => array('admin', 'super')),
                array('controller' => 'Application\Controller\Accounting', 'action' => array(

                    'totals-by-company',
                    'totals-by-company-result',
                    'list-for-company',
                    'list-for-company-result',
                    'office-invoices',
                    'office-invoices-result',
                    'totals-by-office',
                    'totals-by-office-result',
                    'totals-by-adjuster',
                    'totals-by-adjuster-result',
                    'list-for-adjuster',
                    'list-for-adjuster-result',
                ),  'roles' => array('super')),
                array('controller' => 'Application\Controller\Accounting', 'action' => 'quick-books',  'roles' => array('admin', 'super')),

                /*
                *  MAIN - OFFICE
                *
                */
				array('controller' => 'Application\Controller\Office', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\Office', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Office', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                *  MAIN - ADJUSTER
                *
                */
                array('controller' => 'Application\Controller\Adjuster', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\Adjuster', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Adjuster', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Adjuster', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                *  MAIN - INSURANCE COMPANY
                *
                */
                array('controller' => 'Application\Controller\InsuranceCompany', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\InsuranceCompany', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\InsuranceCompany', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\InsuranceCompany', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                *  MAIN - AGENT
                *
                */
                array('controller' => 'Application\Controller\Agent', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\Agent', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Agent', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Agent', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                *  MAIN - LOSS
                *
                */
                array('controller' => 'Application\Controller\Loss', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\Loss', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Loss', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Loss', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                *  MAIN - SETTLEMENT STATUS
                *
                */
                array('controller' => 'Application\Controller\SettlementStatus', 'action' => 'index', 'roles' => array('admin','super')),
                array('controller' => 'Application\Controller\SettlementStatus', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\SettlementStatus', 'action' => 'new', 'roles' => array('super')),
                array('controller' => 'Application\Controller\SettlementStatus', 'action' => 'edit', 'roles' => array('admin','super')),

                /*
                 *  MAIN - PASSWORD
                 *
                 */
                array('controller' => 'Application\Controller\Password', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),



                /*
                *  API - INDEX
                *
                */
                array('controller' => 'Application\Controller\Api\Index', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - USER
                 *
                 */
                array('controller' => 'Application\Controller\Api\User', 'action' => 'index', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'impersonate', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'setoffice', 'roles' => array('user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'getsession', 'roles' => array('user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'list', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'show', 'roles' => array('user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'create', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'ban', 'roles' => array('admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'activate', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'deactivate', 'roles' => array('admin', 'super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'update', 'roles' => array('super')),
                array('controller' => 'Application\Controller\Api\User', 'action' => 'updateself', 'roles' => array('user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - OFFICE
                 *
                 */
                array('controller' => 'Application\Controller\Api\Office', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - ADJUSTER
                 *
                 */
                array('controller' => 'Application\Controller\Api\Adjuster', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - INSURANCE COMPANY
                 *
                 */
                array('controller' => 'Application\Controller\Api\InsuranceCompany', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - AGENT
                 *
                 */
                array('controller' => 'Application\Controller\Api\Agent', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - LOSS
                 *
                 */
                array('controller' => 'Application\Controller\Api\Loss', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - SETTLEMENT STATUS
                 *
                 */
                array('controller' => 'Application\Controller\Api\SettlementStatus', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - STATUS
                 *
                 */
                array('controller' => 'Application\Controller\Api\Status', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - ROLE
                 *
                 */
                array('controller' => 'Application\Controller\Api\Role', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - CONSTANT
                 *
                 */
                array('controller' => 'Application\Controller\Api\Constant', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - PASSWORD
                 *
                 */
                array('controller' => 'Application\Controller\Api\Password', 'roles' => array('guest', 'user', 'adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

				/*
				 *  API - CLAIM
				 *
				 */
				array('controller' => 'Application\Controller\Api\Claim', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - CLAIM ACTIVITY
                 *
                 */
                array('controller' => 'Application\Controller\Api\Activity', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

				/*
                 *  API - CLAIM INVOICE
                 *
                 */
				array('controller' => 'Application\Controller\Api\Invoice', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),

                /*
                 *  API - CLAIM SHORT REPORT
                 *
                 */
                array('controller' => 'Application\Controller\Api\Report\Short', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
				/*
                 *  API - CLAIM LONG REPORT
                 *
                 */
				array('controller' => 'Application\Controller\Api\Report\Long', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
				/*
                 *  API - CLAIM LIABILITY REPORT
                 *
                 */
				array('controller' => 'Application\Controller\Api\Report\Liability', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
				/*
                 *  API - CLAIM PROPERTY DAMAGE REPORT
                 *
                 */
				array('controller' => 'Application\Controller\Api\Report\PropertyDamage', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                /**
                 * API - DOCUMENT CONTROLLER
                 */
                array('controller' => 'Application\Controller\Api\Document', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),


                /**
                 * API - GENERATED REPORT
                 */
                array('controller' => 'Application\Controller\Api\Report\Reports', 'action' => 'recent', 'roles' => array('manager', 'admin', 'super')),

                /**
                 * API - GENERATED REPORT
                 */
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'activity-log', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'notice-of-casualty', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'company-acknowledgement', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'agency-acknowledgement', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'advise-of-settlement', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'single-pdf', 'roles' => array('adjuster', 'general_adjuster', 'manager', 'admin', 'super')),
                array('controller' => 'Application\Controller\Api\GeneratedReport', 'action' => 'invoice', 'roles' => array('admin', 'super')),
			),
			// Maybe you have to use one type of guard (controller vs route) or the other
			// When using both, ACL fails
			//'BjyAuthorize\Guard\Route' => array(
			// Wildcard routes don't seem to work
			//array('route' => 'zfcuser/*', 'roles' => array('guest', 'user')),
			//array('route' => 'zfcuser/login', 'roles' => array('guest')),
			//  array('route' => 'acl', 'roles' => array('user')),
			//),
		),
	),

);