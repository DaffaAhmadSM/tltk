<div class="modal fade" id="detailReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-title text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-alt me-2"></i> {{ $data->SR_TITLE }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-title mb-1">
                                    <i class="fas fa-calendar me-2"></i>Report Date
                                </h6>
                                <p class="mb-0">{{ $data->FORMATTED_DATE }}</p>
                            </div>
                            <button onclick="deleteReport({{ $data->SR_ID }})" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt me-1"></i> Delete Report
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="text-title mb-1">
                                    <i class="fas fa-user-graduate me-2"></i>Student
                                </h6>
                                <p class="mb-0">{{ $data->STUDENT->STUDENT_NAME }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-title mb-1">
                                    <i class="fas fa-users me-2"></i>Parent
                                </h6>
                                <p class="mb-0">{{ $data->STUDENT->PARENT->STUDENT_PARENT_NAME }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-title mb-1">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Teacher
                                </h6>
                                <p class="mb-0">{{ $data->TEACHER->TEACHER_NAME }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-title mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>Report Content
                        </h6>
                        <p class="mb-0">{!! $content !!}</p>
                    </div>
                </div>

                <div class="activities-container">
                    @foreach($data->ACTIVITIES as $activity)
                        <div class="card mb-3 border-0 shadow-sm activity-card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-title">
                                    <i class="fas fa-star me-2"></i>{{ $activity->ACTIVITY_NAME }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            @foreach($activity->REF_ACTIVITIES as $ref)
                                                @php
                                                    $statusClass = '';
                                                    $statusIcon = '';
                                                    $badgeClass = '';

                                                    switch($ref->STATUS) {
                                                        case 'MUNCUL':
                                                            $statusClass = 'text-success';
                                                            $statusIcon = 'check-circle';
                                                            $badgeClass = 'badge bg-success';
                                                            break;
                                                        case 'KURANG':
                                                            $statusClass = 'text-warning';
                                                            $statusIcon = 'exclamation-circle';
                                                            $badgeClass = 'badge bg-warning';
                                                            break;
                                                        case 'BELUM MUNCUL':
                                                            $statusClass = 'text-danger';
                                                            $statusIcon = 'times-circle';
                                                            $badgeClass = 'badge bg-danger';
                                                            break;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @if($ref->ACTIVITY_TYPE)
                                                            <div class="activity-type-header">
                                                                <span class="badge bg-info p-2 text-white rounded-3">{{ $ref->ACTIVITY_TYPE }}</span>
                                                            </div>
                                                        @endif
                                                        <i class="fas fa-angle-right text-primary me-2"></i>
                                                        {{ $ref->ACTIVITY_NAME }}
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="{{ $badgeClass }}"><i class="fas fa-{{ $statusIcon }} me-1"></i>{{ $ref->STATUS }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
