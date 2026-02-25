<link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet"
        href="{{ asset('backend') }}/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="{{ asset('backend') }}/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        /* Gallery Management Styles */
        .gallery-image-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-image-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery-image-card.border-primary {
            border-width: 2px !important;
        }

        .reorder-handle {
            cursor: move;
        }

        .upload-progress {
            height: 6px;
        }

        .gallery-container {
            max-height: 500px;
            overflow-y: auto;
        }

        .image-preview {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .gallery-actions {
            position: absolute;
            top: 8px;
            right: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-image-card:hover .gallery-actions {
            opacity: 1;
        }

        /* Ensure SweetAlert appears above Bootstrap modals */
        .swal2-container {
            z-index: 9999 !important;
        }

        .swal2-popup {
            z-index: 10000 !important;
        }

        .dt-buttons .dt-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            margin: 4px 2px;
            border-radius: 4px;
            transition: opacity 0.3s;
        }

        .dt-buttons .dt-button.buttons-copy {
            background-color: #6c757d;
        }

        .dt-buttons .dt-button.buttons-excel {
            background-color: #28a745;
        }

        .dt-buttons .dt-button.buttons-pdf {
            background-color: #dc3545;
        }

        .dt-buttons .dt-button.buttons-print {
            background-color: #17a2b8;
        }

        .dt-buttons .dt-button:hover {
            opacity: 0.8;
        }

        .event-poster {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 0.25rem;
        }

        .btn-outline-primary,
        .btn-outline-info,
        .btn-outline-danger,
        .btn-outline-warning,
        .btn-outline-success {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        /* Gallery button group styling */
        .gallery-image-card .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .gallery-image-card .btn-group .btn i {
            font-size: 0.75rem;
        }

        .export-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            align-items: center;
        }

        .modal-body .form-control {
            border-radius: 0.375rem;
        }

        #export {
            padding: 0.5rem 2.25rem;
            font-size: 1rem;
        }

        /* Event Status Cards */
        .status-card {
            cursor: pointer;
            transition: all 0.3s ease;
            transform: scale(1);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .status-card.active {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            border: 2px solid #fff !important;
        }

        .status-card .card-body {
            padding: 1.25rem 0.75rem;
        }

        .status-card .badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.5rem;
            min-width: 2rem;
        }

        .events-section {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-weight: 500;
        }

        /* Card layout improvements */
        .status-card .card-title {
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .status-card .card-title {
                font-size: 0.75rem;
            }

            .status-card .card-body {
                padding: 0.75rem 0.5rem;
            }
        }

        /* Empty state styling */
        .empty-state {
            color: #6c757d;
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Table improvements */
        .table th {
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .event-poster {
            transition: transform 0.3s ease;
        }

        .event-poster:hover {
            transform: scale(1.1);
        }

        /* Toggle buttons styling */
        .toggle-active,
        .toggle-format {
            text-decoration: none;
            display: inline-block;
            transition: transform 0.2s ease;
        }

        .toggle-active:hover,
        .toggle-format:hover {
            transform: scale(1.1);
            text-decoration: none;
        }

        .toggle-active i,
        .toggle-format i {
            transition: all 0.3s ease;
        }

        /* Active toggle state */
        .fa-toggle-on {
            color: #28a745 !important;
        }

        .fa-toggle-off {
            color: #dc3545 !important;
        }
    </style>