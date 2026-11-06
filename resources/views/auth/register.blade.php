@extends('layouts.app')

@section('title', 'Join TEFC Portal — Exporter & Buyer Registration')

@section('styles')
<style>
.auth-screen {
  min-height: 90vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  background: linear-gradient(135deg, var(--sky2) 0%, #fff 100%);
}
.auth-card {
  background: #fff;
  width: 100%;
  max-width: 950px;
  border-radius: 30px;
  padding: 3.5rem;
  box-shadow: 0 20px 80px rgba(108, 21, 63, 0.06);
  border: 1px solid var(--border);
  position: relative;
  overflow: hidden;
}
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
}
.form-grid-full {
  grid-column: span 2;
}
.section-title {
  grid-column: span 2;
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--primary);
  border-bottom: 2px solid var(--sky);
  padding-bottom: 0.5rem;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}
.export-conditional-field {
  display: none;
}
@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
  .form-grid-full, .section-title {
    grid-column: span 1;
  }
}
.auth-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 6px;
  background: linear-gradient(to right, var(--primary) 0%, var(--accent) 100%);
}
.auth-head { text-align: center; margin-bottom: 2.5rem; }
.auth-head h2 { color: var(--primary); font-size: 2.4rem; font-weight: 800; margin-bottom: 0.5rem; }
.auth-head p { color: var(--muted); font-size: 1.05rem; }

.form-group { margin-bottom: 1.5rem; position: relative; }
.form-group label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--primary); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.5px; }
.form-control { width: 100%; padding: 1.1rem; border: 2px solid #f0f0f0; border-radius: 14px; font-size: 1rem; transition: all 0.3s; color: var(--text); }
.form-control:focus { border-color: var(--primary-light); outline: none; background: var(--sky2); box-shadow: 0 0 0 4px rgba(108, 21, 63, 0.05); }

/* Seller Section Styling */
.seller-section {
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  border-top: 1px dashed var(--border);
  margin-top: 1.5rem;
  padding-top: 1.5rem;
}
.seller-section.show {
  max-height: 5000px;
  opacity: 1;
}

/* Buyer Section Styling (mirrors seller) */
.buyer-section {
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  border-top: 1px dashed var(--border);
  margin-top: 1.5rem;
  padding-top: 1.5rem;
}
.buyer-section.show {
  max-height: 3000px;
  opacity: 1;
}

.input-icon-group {
  position: relative;
}
.input-status-badge {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.9rem;
  font-weight: 700;
  pointer-events: none;
  display: none;
}
.input-status-badge.success {
  color: #2e7d32;
  display: block;
}
.input-status-badge.error {
  color: #c62828;
  display: block;
}
.input-status-badge.loading {
  color: var(--muted);
  display: block;
}

.verification-status {
  margin-top: 0.5rem;
  font-size: 0.85rem;
  font-weight: 600;
  display: none;
}
.verification-status.success {
  color: #2e7d32;
  display: block;
}
.verification-status.error {
  color: #c62828;
  display: block;
}

.btn-auth { background: var(--primary); color: #fff; width: 100%; padding: 1.2rem; border: none; border-radius: 14px; font-size: 1.15rem; font-weight: 700; cursor: pointer; transition: all 0.3s; margin-top: 1rem; }
.btn-auth:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(108, 21, 63, 0.25); }

.file-upload-wrapper {
  border: 2px dashed var(--border);
  padding: 2rem;
  border-radius: 14px;
  text-align: center;
  background: var(--sky2);
  cursor: pointer;
  transition: all 0.3s;
}
.file-upload-wrapper:hover {
  border-color: var(--primary);
  background: #fff;
}
.file-upload-wrapper input[type="file"] {
  display: none;
}
.file-upload-text {
  font-weight: 600;
  color: var(--primary);
}
.file-upload-info {
  font-size: 0.8rem;
  color: var(--muted);
  margin-top: 0.3rem;
}

.auth-footer { text-align: center; margin-top: 2rem; font-size: 0.95rem; color: var(--muted); }
.auth-footer a { color: var(--primary); font-weight: 700; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }

.input-locked-gst {
  background-color: #f1f5f9 !important;
  color: #334155 !important;
  border-color: #cbd5e1 !important;
  cursor: not-allowed !important;
}
.gst-lock-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: #ecfdf5;
  color: #065f46;
  border: 1px solid #a7f3d0;
  font-size: 0.74rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 6px;
  margin-top: 4px;
}
.audit-metric-box {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 0.8rem 0.95rem;
  transition: all 0.2s;
}
.audit-metric-box:hover {
  border-color: #cbd5e1;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
</style>
@endsection

@section('content')
<div class="auth-screen">
  <div class="auth-card">
    <div class="auth-head">
      <h2>Create Your Account</h2>
      <p>Connect with the Textiles Export Facilitation Centre</p>
    </div>

    @if(!empty($errors) && (is_array($errors) ? count($errors) > 0 : $errors->any()))
      <div style="background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
        <ul style="margin: 0; padding-left: 1.2rem;">
          @foreach(is_array($errors) ? $errors : $errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ url('/register') }}" method="POST" enctype="multipart/form-data" id="registerForm">
      @csrf
      
      <!-- Registration Category Selector -->
      <div class="form-grid mb-3">
        <div class="form-group form-grid-full">
          <label>I am registering as a...</label>
          <select name="role" id="roleSelector" class="form-control" required>
            <option value="exporter" {{ in_array(old('role', request('role', 'exporter')), ['exporter', 'seller']) ? 'selected' : '' }}>Exporter / Manufacturer (Higher Authority Account)</option>
            <option value="buyer" {{ old('role', request('role')) == 'buyer' ? 'selected' : '' }}>Buyer (Sourcing Indian Textiles)</option>
          </select>
        </div>
      </div>

      <!-- Higher Authority / Primary Contact Details -->
      <div class="form-grid">
        <div class="section-title" id="primaryDetailsTitle">👤 Higher Authority / Authorized Signatory Details</div>

        <div class="form-group">
          <label id="nameLabel">Authorized Signatory / Higher Authority Name <span style="color:#c62828">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Rajesh Kumar Sharma" required value="{{ old('name') }}">
          <small id="nameHelp" style="color: var(--muted); font-size: 0.78rem;">Managing Director, Director, Partner, Proprietor, or Authorized Officer</small>
        </div>

        <div class="form-group" id="designationGroup">
          <label>Higher Authority Designation <span style="color:#c62828">*</span></label>
          <select name="designation" id="designationInput" class="form-control" required>
            <option value="">-- Select Authority Designation --</option>
            <option value="Managing Director" {{ old('designation') == 'Managing Director' ? 'selected' : '' }}>Managing Director (MD)</option>
            <option value="Director" {{ old('designation') == 'Director' ? 'selected' : '' }}>Director</option>
            <option value="Partner" {{ old('designation') == 'Partner' ? 'selected' : '' }}>Partner</option>
            <option value="Proprietor" {{ old('designation') == 'Proprietor' ? 'selected' : '' }}>Proprietor</option>
            <option value="Chief Executive Officer" {{ old('designation') == 'Chief Executive Officer' ? 'selected' : '' }}>Chief Executive Officer (CEO)</option>
            <option value="Authorized Signatory" {{ old('designation') == 'Authorized Signatory' ? 'selected' : '' }}>Authorized Signatory</option>
          </select>
          <small style="color: var(--muted); font-size: 0.78rem;">Main exporter login will verify & acknowledge data entered by operators.</small>
        </div>

        <div class="form-group">
          <label>Official Email ID (Primary Login) <span style="color:#c62828">*</span></label>
          <input type="email" name="email" class="form-control" placeholder="director@company.com" required value="{{ old('email') }}">
        </div>

        <div class="form-group" id="phoneGroup">
          <label id="phoneLabel">Official Mobile No <span style="color:#c62828">*</span></label>
          <input type="text" name="contact_phone" id="phoneInput" class="form-control" placeholder="e.g. +91 98765 43210" required value="{{ old('contact_phone') }}">
        </div>
      </div>

      <!-- Exporter Specific Fields -->
      <div class="seller-section {{ in_array(old('role', request('role', 'exporter')), ['exporter', 'seller']) ? 'show' : '' }}" id="sellerSection" style="display: {{ in_array(old('role', request('role', 'exporter')), ['exporter', 'seller']) ? 'block' : 'none' }};">
        
        <div style="background: rgba(108, 21, 63, 0.05); border-left: 4px solid var(--primary); padding: 1.1rem 1.3rem; border-radius: 4px 12px 12px 4px; margin-bottom: 1.5rem;">
          <h4 style="color: var(--primary-dark); font-weight: 800; margin-bottom: 0.3rem; font-size: 0.95rem;">🏛️ Exporter Higher Authority Onboarding & Two-Tier Workflow</h4>
          <p style="font-size: 0.85rem; color: var(--muted); margin: 0; line-height: 1.5;">
            Once submitted, your application is reviewed and verified by your designated regional TEFC Centre. Once activated, your higher authority login can create <strong>Data Entry Operator (DEO)</strong> logins for your staff. DEO operators can upload products, which will await your acknowledgment before being published directly to the public catalogue (no HO admin verification required).
          </p>
        </div>

        <div class="form-grid">
          <h3 class="section-title">🏢 Company & Primary Information</h3>
          
          <div class="form-group">
            <label>GSTIN (GST No) <span style="color:#c62828">*</span></label>
            <div style="display: flex; gap: 8px; align-items: stretch;">
              <div style="flex: 1; position: relative;">
                <input type="text" name="gst_no" id="gstInput" class="form-control" placeholder="e.g. 27AAACT2727Q1ZW" maxlength="15" value="{{ old('gst_no') }}" style="text-transform: uppercase; font-family: monospace; font-size: 0.95rem; font-weight: 700; letter-spacing: 0.5px;">
              </div>
              <button type="button" id="btnFetchGst" class="btn" style="background: var(--primary); color: #fff; border: none; border-radius: 12px; padding: 0.6rem 1.2rem; font-weight: 700; font-size: 0.88rem; white-space: nowrap; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(108, 21, 63, 0.15); transition: all 0.2s;">
                <i class="fas fa-search" id="btnFetchGstIcon"></i> <span id="btnFetchGstText">Fetch Details</span>
              </button>
              <button type="button" id="btnResetGst" class="btn" style="display: none; background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; border-radius: 12px; padding: 0.6rem 1rem; font-weight: 600; font-size: 0.85rem; cursor: pointer;">
                <i class="fas fa-undo me-1"></i> Change GSTIN
              </button>
            </div>
            <div class="verification-status" id="gstStatus" style="margin-top: 0.45rem;"></div>
            <div id="gstCharCounter" style="font-size: 0.78rem; color: var(--muted); margin-top: 0.3rem;">
              Enter 15-character GSTIN. Taxpayer legal name, address &amp; cluster will auto-fill from official Swipe GST records.
            </div>
          </div>

          <div class="form-group">
            <label>IEC (Import Export Code)</label>
            <div class="input-icon-group">
              <input type="text" name="iec_no" id="iecInput" class="form-control" placeholder="e.g. 0123456789 (Optional)" maxlength="10" value="{{ old('iec_no') }}">
              <div class="input-status-badge" id="iecBadge"></div>
            </div>
            <small style="color: var(--muted); font-size: 0.8rem; margin-top: 0.3rem; display: block;">10-digit code issued by DGFT (Optional. Entering IEC automatically activates Exporter status).</small>
          </div>

          <!-- GSTIN Official Taxpayer Verification & Authority Cross-Check Dossier -->
          <div class="form-group form-grid-full" id="gstVerificationCard" style="display: none; margin-top: -0.5rem; margin-bottom: 1.2rem;">
            <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 1.25rem 1.4rem; position: relative;">
              <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem; margin-bottom: 0.9rem;">
                <div style="display: flex; align-items: center; gap: 8px;">
                  <span style="font-size: 1.25rem;">🏛️</span>
                  <div>
                    <strong style="font-size: 0.95rem; color: #0f172a;">Official GSTIN Taxpayer Record &amp; Authority Cross-Check</strong>
                    <div style="font-size: 0.75rem; color: #64748b;">Verified via Swipe Taxpayer Gateway</div>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <span id="gstLiveBadge" class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem; padding: 6px 10px;">Verified</span>
                  <span class="badge bg-success" id="gstRecordStatus" style="padding: 6px 10px;">Active</span>
                </div>
              </div>

              <!-- Enterprise Statutory Details -->
              <div class="row g-2 mb-3" style="font-size: 0.88rem; background: #ffffff; padding: 0.85rem; border-radius: 10px; border: 1px solid #e2e8f0;">
                <div class="col-md-6">
                  <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Legal Entity Name (lgnm)</div>
                  <div id="gstLegalNameDisplay" style="font-weight: 700; color: #0f172a;">-</div>
                </div>
                <div class="col-md-6">
                  <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Trade Name (tradeNam)</div>
                  <div id="gstTradeNameDisplay" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div class="col-md-6 mt-2">
                  <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Constitution of Business</div>
                  <div id="gstConstitutionDisplay" style="font-weight: 600; color: #334155;">-</div>
                </div>
                <div class="col-md-6 mt-2">
                  <div style="color: #64748b; font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Registered Promoters / Signatories</div>
                  <div id="gstSignatoriesDisplay" style="font-weight: 600; color: #334155;">-</div>
                </div>
              </div>

              <!-- Higher Authority ⇄ Enterprise Cross-Authentication Matrix -->
              <div style="border-top: 1px dashed #cbd5e1; padding-top: 0.9rem;">
                <div style="font-size: 0.82rem; font-weight: 800; text-transform: uppercase; color: #475569; margin-bottom: 0.6rem; display: flex; align-items: center; gap: 6px;">
                  <span>🛡️</span> Higher Authority ⇄ Enterprise Authentication Audit
                </div>

                <div class="row g-2">
                  <!-- Check 1: Authority Name Match -->
                  <div class="col-md-6">
                    <div class="audit-metric-box" id="auditNameBox">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">1. Signatory Name Match</span>
                        <span id="auditNameBadge" class="badge bg-secondary" style="font-size: 0.7rem;">Checking...</span>
                      </div>
                      <div id="auditNameText" style="font-size: 0.8rem; color: #334155; line-height: 1.35;">-</div>
                    </div>
                  </div>

                  <!-- Check 2: Designation Alignment -->
                  <div class="col-md-6">
                    <div class="audit-metric-box" id="auditDesignationBox">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">2. Constitution Alignment</span>
                        <span id="auditDesignationBadge" class="badge bg-secondary" style="font-size: 0.7rem;">Checking...</span>
                      </div>
                      <div id="auditDesignationText" style="font-size: 0.8rem; color: #334155; line-height: 1.35;">-</div>
                    </div>
                  </div>

                  <!-- Check 3: Corporate Domain Match -->
                  <div class="col-md-6">
                    <div class="audit-metric-box" id="auditDomainBox">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">3. Email Domain Identity</span>
                        <span id="auditDomainBadge" class="badge bg-secondary" style="font-size: 0.7rem;">Checking...</span>
                      </div>
                      <div id="auditDomainText" style="font-size: 0.8rem; color: #334155; line-height: 1.35;">-</div>
                    </div>
                  </div>

                  <!-- Check 4: Statutory Document Check -->
                  <div class="col-md-6">
                    <div class="audit-metric-box" id="auditProofBox">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span style="font-size: 0.75rem; font-weight: 700; color: #475569;">4. Statutory REG-06 Proof</span>
                        <span class="badge bg-primary" style="font-size: 0.7rem;">Mandatory</span>
                      </div>
                      <div style="font-size: 0.8rem; color: #334155; line-height: 1.35;">
                        Form GST REG-06 (Annexure B with photos &amp; signatories) must be attached below for Regional Centre physical verification.
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Overall Guidance Banner -->
                <div id="gstOwnershipBanner" style="margin-top: 0.85rem; padding: 0.85rem 1rem; border-radius: 10px; font-size: 0.84rem; display: flex; align-items: flex-start; gap: 10px;">
                  <!-- Dynamically filled by JS -->
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Manufacturer Name / Company Name <span style="color:#c62828">*</span></label>
            <input type="text" name="company_name" id="companyNameInput" class="form-control" placeholder="Company Name (auto-fills from GST)" value="{{ old('company_name') }}">
            <div id="companyNameLockBadge" class="gst-lock-pill" style="display: none;">
              <i class="fas fa-lock"></i> Auto-filled &amp; locked from official GSTIN record
            </div>
          </div>

          <div class="form-group">
            <label>Year of Establishment <span style="color:#c62828">*</span></label>
            <input type="number" name="year_of_establishment" id="yearInput" class="form-control" placeholder="e.g. 2015" min="1800" max="{{ date('Y') }}" value="{{ old('year_of_establishment') }}">
            <div id="yearLockBadge" class="gst-lock-pill" style="display: none;">
              <i class="fas fa-lock"></i> Auto-filled &amp; locked from official GSTIN record
            </div>
          </div>

          <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" id="websiteInput" class="form-control" placeholder="https://www.mycompany.com" value="{{ old('website') }}">
          </div>

          <h3 class="section-title">📍 Cluster Location & TEFC Centre Assignment</h3>

          <div class="form-group form-grid-full" style="background: #fdf6f0; border: 1px solid #f2cfbd; border-radius: 12px; padding: 1rem 1.2rem; margin-bottom: 0.5rem;">
            <div style="display: flex; align-items: flex-start; gap: 10px;">
              <span style="font-size: 1.3rem; line-height: 1;">🏛️</span>
              <div style="font-size: 0.86rem; color: #4a1525; line-height: 1.4;">
                <strong>TEFC Centre Cluster Coverage:</strong>
                Registration is strictly limited to units located within the designated revenue districts of the 6 operational TEFC Centres (<strong>Surat, Karur, Ichalkaranji, Jaipur, Varanasi, and Ludhiana</strong>). Units outside these jurisdictions cannot be facilitated at this time.
              </div>
            </div>
          </div>

          <div class="form-group form-grid-full">
            <label>Factory Address <span style="color:#c62828">*</span></label>
            <textarea name="factory_address" id="factoryAddressInput" class="form-control" rows="3" placeholder="Factory Address (auto-fills from GST)" style="resize: vertical;">{{ old('factory_address') }}</textarea>
            <div id="factoryAddressLockBadge" class="gst-lock-pill" style="display: none;">
              <i class="fas fa-lock"></i> Auto-filled &amp; locked from official GSTIN record
            </div>
          </div>

          <div class="form-group">
            <label>State (Cluster State) <span style="color:#c62828">*</span></label>
            <select name="state" id="stateSelector" class="form-control">
              <option value="">-- Select Eligible State --</option>
              @if(isset($eligibleDistricts))
                @foreach($eligibleDistricts as $stateName => $distList)
                  <option value="{{ $stateName }}" {{ old('state') == $stateName ? 'selected' : '' }}>{{ $stateName }}</option>
                @endforeach
              @endif
            </select>
          </div>

          <div class="form-group">
            <label>District / Taluk (Designated Coverage) <span style="color:#c62828">*</span></label>
            <select name="district" id="districtSelector" class="form-control">
              <option value="">-- First Select State --</option>
            </select>
            <div id="assignedCentreBadge" style="display: none; margin-top: 0.6rem; padding: 0.6rem 0.9rem; border-radius: 10px; background: #e8f5e9; border: 1px solid #a5d6a7; color: #1b5e20; font-size: 0.85rem; font-weight: 700;">
              <i class="fas fa-check-circle" style="color: #2e7d32;"></i> Assigned TEFC Centre: <span id="centreNameText">Surat</span> Centre
            </div>
            <div id="districtWarning" style="display: none; margin-top: 0.6rem; padding: 0.6rem 0.9rem; border-radius: 10px; background: #ffebee; border: 1px solid #ffcdd2; color: #b71c1c; font-size: 0.85rem; font-weight: 600;">
              ⚠️ TEFC facilitation centre is currently not available for this district. Exporter registration is limited to designated cluster districts.
            </div>
          </div>

          <h3 class="section-title">⚙️ Production & Machinery Details</h3>

          <div class="form-group">
            <label>Textile Segment <span style="color:#c62828">*</span></label>
            <select name="textile_segment" id="segmentSelector" class="form-control">
              <option value="">Select Segment</option>
              <option value="Spinning" {{ old('textile_segment') == 'Spinning' ? 'selected' : '' }}>Spinning</option>
              <option value="Weaving" {{ old('textile_segment') == 'Weaving' ? 'selected' : '' }}>Weaving</option>
              <option value="Knitting" {{ old('textile_segment') == 'Knitting' ? 'selected' : '' }}>Knitting</option>
              <option value="Processing" {{ old('textile_segment') == 'Processing' ? 'selected' : '' }}>Processing</option>
              <option value="Garmenting" {{ old('textile_segment') == 'Garmenting' ? 'selected' : '' }}>Garmenting</option>
              <option value="Technical Textiles" {{ old('textile_segment') == 'Technical Textiles' ? 'selected' : '' }}>Technical Textiles</option>
              <option value="Composite" {{ old('textile_segment') == 'Composite' ? 'selected' : '' }}>Composite Mill</option>
              <option value="Non-Woven" {{ old('textile_segment') == 'Non-Woven' ? 'selected' : '' }}>Non-Woven</option>
              <option value="Other" {{ old('textile_segment') == 'Other' || old('textile_segment_other') ? 'selected' : '' }}>Other</option>
            </select>
            <div id="otherSegmentGroup" style="display: {{ old('textile_segment') == 'Other' || old('textile_segment_other') ? 'block' : 'none' }}; margin-top: 0.55rem;">
              <label style="font-size: 0.8rem; font-weight: 600; color: var(--primary); margin-bottom: 0.25rem; display: block;">
                Specify Other Textile Segment <span style="color:#c62828">*</span>
              </label>
              <input type="text" name="textile_segment_other" id="otherSegmentInput" class="form-control" placeholder="e.g. Embroidery, Handloom, Made-ups, Dyeing" value="{{ old('textile_segment_other') }}">
            </div>
          </div>

          <div class="form-group">
            <label>Main Product (Existing) HSN Code <span style="color:#c62828">*</span></label>
            <input type="text" name="main_product_hsn" id="hsnInput" class="form-control" placeholder="e.g. 5208, 6204" value="{{ old('main_product_hsn') }}">
          </div>

          <div class="form-group">
            <label>Installed Machine Capacity <span style="color:#c62828">*</span></label>
            <input type="text" name="installed_capacity" id="capacityInput" class="form-control" placeholder="e.g. 50,000" value="{{ old('installed_capacity') }}">
          </div>

          <div class="form-group">
            <label>Capacity Unit <span style="color:#c62828">*</span></label>
            <select name="capacity_unit" id="capacityUnitSelector" class="form-control">
              <option value="">Select Unit</option>
              <option value="Meters/Month" {{ old('capacity_unit') == 'Meters/Month' ? 'selected' : '' }}>Meters/Month</option>
              <option value="Tons/Month" {{ old('capacity_unit') == 'Tons/Month' ? 'selected' : '' }}>Tons/Month</option>
              <option value="Pieces/Month" {{ old('capacity_unit') == 'Pieces/Month' ? 'selected' : '' }}>Pieces/Month</option>
              <option value="Kgs/Month" {{ old('capacity_unit') == 'Kgs/Month' ? 'selected' : '' }}>Kilograms/Month</option>
            </select>
          </div>

          <div class="form-group">
            <label>Monthly Production <span style="color:#c62828">*</span></label>
            <input type="text" name="monthly_production" id="monthlyProductionInput" class="form-control" placeholder="e.g. 40,000" value="{{ old('monthly_production') }}">
          </div>

          <div class="form-group">
            <label>Annual Turnover <span style="color:#c62828">*</span></label>
            <select name="annual_turnover" id="turnoverSelector" class="form-control">
              <option value="">Select Turnover Range</option>
              <option value="Upto 1 Crore" {{ old('annual_turnover') == 'Upto 1 Crore' ? 'selected' : '' }}>Upto 1 Crore</option>
              <option value="1 to 5 Crores" {{ old('annual_turnover') == '1 to 5 Crores' ? 'selected' : '' }}>1 to 10 Crores</option>
              <option value="5 to 25 Crores" {{ old('annual_turnover') == '5 to 25 Crores' ? 'selected' : '' }}>10 to 100 Crores</option>
              <option value="25 to 100 Crores" {{ old('annual_turnover') == '25 to 100 Crores' ? 'selected' : '' }}>100 to 500 Crores</option>
              <option value="Above 100 Crores" {{ old('annual_turnover') == 'Above 100 Crores' ? 'selected' : '' }}>Above 500 Crores</option>
            </select>
          </div>

          <div class="form-group form-grid-full">
            <label>Major Machineries / Technology used for manufacturing <span style="color:#c62828">*</span></label>
            <textarea name="major_machineries" id="machineriesInput" class="form-control" rows="3" placeholder="Describe main plant machinery and technical integrations..." style="resize: vertical;">{{ old('major_machineries') }}</textarea>
          </div>

          <h3 class="section-title">🚢 Trade & Export Status</h3>

          <div class="form-group form-grid-full">
            <label>Are you an Exporter? <span style="color:#c62828">*</span></label>
            <select name="is_exporter" id="isExporterSelector" class="form-control">
              <option value="N" {{ old('is_exporter') == 'N' ? 'selected' : '' }}>No</option>
              <option value="Y" {{ old('is_exporter', 'Y') == 'Y' ? 'selected' : '' }}>Yes</option>
            </select>
          </div>

          <div class="form-group export-conditional-field">
            <label>Export Turnover <span style="color:#c62828" id="exportTurnoverAsterisk">*</span></label>
            <input type="text" name="export_turnover" id="exportTurnoverInput" class="form-control" placeholder="e.g. 5 Crores" value="{{ old('export_turnover') }}">
          </div>

          <div class="form-group export-conditional-field">
            <label>Major Exporting Countries <span style="color:#c62828" id="exportCountriesAsterisk">*</span></label>
            <input type="text" name="major_exporting_countries" id="exportCountriesInput" class="form-control" placeholder="e.g. USA, UK, Germany" value="{{ old('major_exporting_countries') }}">
          </div>

          <div class="form-group form-grid-full">
            <label>Export Potential</label>
            <textarea name="export_potential" class="form-control" rows="2" placeholder="Detail your exporting capabilities, desired markets and potential..." style="resize: vertical;">{{ old('export_potential') }}</textarea>
          </div>

          <h3 class="section-title">📜 Certifications, Remarks & Media</h3>

          <div class="form-group">
            <label>Certifications (e.g. GOTS, ISO, OEKO-TEX)</label>
            <input type="text" name="certifications" class="form-control" placeholder="e.g. OEKO-TEX Standard 100, ISO 9001" value="{{ old('certifications') }}">
          </div>

          <div class="form-group">
            <label>Upload Form GST REG-06 / Proof of Business & Authority <span style="color:#c62828">*</span></label>
            <div class="file-upload-wrapper" onclick="document.getElementById('certificateFile').click()">
              <div class="file-upload-text" id="uploadText">📁 Upload Form GST REG-06 / Business Proof</div>
              <div class="file-upload-info">PDF, JPG, JPEG, PNG (Max 10MB) — Annexure B showing authorized signatories</div>
              <input type="file" name="certificate" id="certificateFile" accept=".pdf,.jpg,.jpeg,.png">
            </div>
            <small style="color: var(--muted); font-size: 0.78rem; margin-top: 0.35rem; display: block;">
              Official Certificate of Registration (Form GST REG-06) proves you are the legitimate taxpayer or authorized signatory.
            </small>
          </div>

          <!-- <div class="form-group form-grid-full">
            <label>Existing Product Images</label>
            <div class="file-upload-wrapper" onclick="document.getElementById('productImagesFile').click()">
              <div class="file-upload-text" id="productImagesText">📷 Upload Product Images (Multiple allowed)</div>
              <div class="file-upload-info">JPG, JPEG, PNG (Max 10MB per file)</div>
              <input type="file" name="product_images[]" id="productImagesFile" accept=".jpg,.jpeg,.png" multiple>
            </div>
            <div id="productImagesPreview" style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;"></div>
          </div> -->

          <div class="form-group form-grid-full">
            <label>Remarks</label>
            <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional comments or business descriptions..." style="resize: vertical;">{{ old('remarks') }}</textarea>
          </div>

          <!-- Authorized Signatory Affirmation -->
          <div class="form-group form-grid-full" id="authorityDeclarationCard" style="margin-top: 1.5rem; background: #fdfbf7; border: 1.5px solid #e2d9cc; border-radius: 14px; padding: 1.25rem 1.4rem;">
            <div style="display: flex; gap: 14px; align-items: flex-start;">
              <input type="checkbox" name="authority_declaration" id="authorityDeclarationCheckbox" value="1" style="width: 22px; height: 22px; margin-top: 3px; accent-color: var(--primary); cursor: pointer; flex-shrink: 0;">
              <label for="authorityDeclarationCheckbox" style="cursor: pointer; font-size: 0.88rem; line-height: 1.55; color: #334155; margin: 0;">
                <strong style="color: var(--primary-dark); display: block; margin-bottom: 0.3rem; font-size: 0.95rem;">
                  ⚖️ Authorized Signatory Undertaking <span style="color:#c62828">*</span>
                </strong>
                <span id="authorityDeclarationText">
                  I, <strong id="declarantName" style="color: #0f172a;">[Authorized Signatory]</strong>, serving as <strong id="declarantDesignation" style="color: #0f172a;">[Higher Authority]</strong>, affirm that I am authorized on behalf of <strong id="declarantCompany" style="color: #0f172a;">[Enterprise]</strong> (GSTIN: <strong id="declarantGstin" style="color: #0f172a;">[GSTIN]</strong>) to register this account, create Data Entry Operator logins for staff, and facilitate textile export activities on the TEFC Portal. All statutory details submitted are true and authentic.
                </span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- Buyer Specific Fields -->
      <div class="buyer-section {{ old('role', request('role')) === 'buyer' ? 'show' : '' }}" id="buyerSection" style="display: {{ old('role', request('role')) === 'buyer' ? 'block' : 'none' }};">
        <div style="background: rgba(14, 165, 233, 0.07); border-left: 4px solid #0ea5e9; padding: 1rem 1.2rem; border-radius: 4px 12px 12px 4px; margin-bottom: 1.5rem;">
          <h4 style="color: var(--primary-dark); font-weight: 800; margin-bottom: 0.3rem; font-size: 0.95rem;">🛒 Buyer Sourcing Profile</h4>
          <p style="font-size: 0.85rem; color: var(--muted); margin: 0;">Tell us about your organisation so Indian textile exporters can connect with you.</p>
        </div>

        <div class="form-grid">
          <h3 class="section-title">🏢 Organisation Details</h3>

          <div class="form-group form-grid-full">
            <label>Company / Organisation Name <span style="color:#c62828">*</span></label>
            <input type="text" name="company_name" id="buyerCompanyName" class="form-control" placeholder="e.g. Global Garments Inc." value="{{ old('company_name') }}">
          </div>

          <h3 class="section-title">📍 Address & Location</h3>

          <div class="form-group form-grid-full">
            <label>Business Address <span style="color:#c62828">*</span></label>
            <textarea name="address" id="buyerAddress" class="form-control" rows="3" placeholder="Street, City, State / Province, PIN / ZIP" style="resize: vertical;">{{ old('address') }}</textarea>
          </div>

          <div class="form-group">
            <label>Country</label>
            <input type="text" name="country" id="buyerCountry" class="form-control" placeholder="e.g. United States" value="{{ old('country') }}">
          </div>

          <div class="form-group">
            <label>Website</label>
            <input type="url" name="website" id="buyerWebsite" class="form-control" placeholder="https://www.mycompany.com" value="{{ old('website') }}">
          </div>

          <h3 class="section-title">📋 Sourcing Requirements</h3>

          <div class="form-group form-grid-full">
            <label>Sourcing / Procurement Requirements (Optional)</label>
            <textarea name="sourcing_requirements" class="form-control" rows="3" placeholder="Describe the types of textiles, quantities, or product categories you are sourcing..." style="resize: vertical;">{{ old('sourcing_requirements') }}</textarea>
          </div>
        </div>
      </div>

      <!-- Passwords -->
      <div class="form-grid mt-4">
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="********" required>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" name="password_confirmation" class="form-control" placeholder="********" required>
        </div>
      </div>

      <button type="submit" class="btn-auth">Create Account</button>
    </form>

    <div class="auth-footer">
      Already have an account? <a href="{{ url('/login') }}">Login here</a>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelector = document.getElementById('roleSelector');
    const sellerSection = document.getElementById('sellerSection');
    const registerForm = document.getElementById('registerForm');
    
    const gstInput = document.getElementById('gstInput');
    const gstBadge = document.getElementById('gstBadge');
    const gstStatus = document.getElementById('gstStatus');
    
    const iecInput = document.getElementById('iecInput');
    const iecBadge = document.getElementById('iecBadge');
    
    const designationInput = document.getElementById('designationInput');
    const designationGroup = document.getElementById('designationGroup');
    const nameLabel = document.getElementById('nameLabel');
    const nameHelp = document.getElementById('nameHelp');
    const companyNameInput = document.getElementById('companyNameInput');
    const phoneInput = document.getElementById('phoneInput');
    const websiteInput = document.getElementById('websiteInput');
    
    const yearInput = document.getElementById('yearInput');
    const factoryAddressInput = document.getElementById('factoryAddressInput');
    
    // Cluster jurisdiction dropdowns
    const stateSelector = document.getElementById('stateSelector');
    const districtSelector = document.getElementById('districtSelector');
    const assignedCentreBadge = document.getElementById('assignedCentreBadge');
    const centreNameText = document.getElementById('centreNameText');
    const districtWarning = document.getElementById('districtWarning');
    const eligibleDistricts = @json($eligibleDistricts ?? []);
    
    const segmentSelector = document.getElementById('segmentSelector');
    const otherSegmentGroup = document.getElementById('otherSegmentGroup');
    const otherSegmentInput = document.getElementById('otherSegmentInput');
    const hsnInput = document.getElementById('hsnInput');
    const capacityInput = document.getElementById('capacityInput');
    const capacityUnitSelector = document.getElementById('capacityUnitSelector');
    const monthlyProductionInput = document.getElementById('monthlyProductionInput');
    const machineriesInput = document.getElementById('machineriesInput');
    const turnoverSelector = document.getElementById('turnoverSelector');
    
    const isExporterSelector = document.getElementById('isExporterSelector');
    const exportConditionalFields = document.querySelectorAll('.export-conditional-field');
    const exportTurnoverInput = document.getElementById('exportTurnoverInput');
    const exportCountriesInput = document.getElementById('exportCountriesInput');
    
    const certificateFile = document.getElementById('certificateFile');
    const uploadText = document.getElementById('uploadText');
    const productImagesFile = document.getElementById('productImagesFile');
    const productImagesText = document.getElementById('productImagesText');
    const productImagesPreview = document.getElementById('productImagesPreview');

    const primaryDetailsTitle = document.getElementById('primaryDetailsTitle');
    const phoneLabel = document.getElementById('phoneLabel');
    const buyerSection = document.getElementById('buyerSection');
    const buyerCompanyName = document.getElementById('buyerCompanyName');
    const buyerAddress = document.getElementById('buyerAddress');

    // Dynamic District Population based on State
    function populateDistricts(selectedState, preselectedDistrict = '') {
        districtSelector.innerHTML = '<option value="">-- Select Covered District / Taluk --</option>';
        assignedCentreBadge.style.display = 'none';
        districtWarning.style.display = 'none';

        if (selectedState && eligibleDistricts[selectedState]) {
            const list = eligibleDistricts[selectedState];
            list.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.district;
                opt.textContent = item.district + ' (' + item.centre_name + ' Centre)';
                opt.dataset.centre = item.centre_name;
                opt.dataset.centreCode = item.centre_code;
                if (preselectedDistrict && (item.district.toLowerCase() === preselectedDistrict.toLowerCase() || preselectedDistrict.toLowerCase().includes(item.district.toLowerCase()))) {
                    opt.selected = true;
                }
                districtSelector.appendChild(opt);
            });

            if (districtSelector.value) {
                updateAssignedCentre();
            }
        }
    }

    function updateAssignedCentre() {
        const selectedOpt = districtSelector.options[districtSelector.selectedIndex];
        if (selectedOpt && selectedOpt.dataset.centre) {
            centreNameText.textContent = selectedOpt.dataset.centre;
            assignedCentreBadge.style.display = 'block';
            districtWarning.style.display = 'none';
        } else {
            assignedCentreBadge.style.display = 'none';
        }
    }

    if (stateSelector) {
        stateSelector.addEventListener('change', function () {
            populateDistricts(this.value);
        });
    }

    if (districtSelector) {
        districtSelector.addEventListener('change', function () {
            updateAssignedCentre();
        });
    }

    // Initialize districts if state preselected (e.g. old input)
    const initialOldState = "{{ old('state') }}";
    const initialOldDistrict = "{{ old('district') }}";
    if (initialOldState && stateSelector) {
        populateDistricts(initialOldState, initialOldDistrict);
    }

    // Toggle exporter fields
    function toggleExportFields() {
        const isExpRole = roleSelector.value === 'exporter' || roleSelector.value === 'seller';
        if (isExporterSelector.value === 'Y') {
            exportConditionalFields.forEach(el => el.style.display = 'block');
            if (isExpRole) {
                exportTurnoverInput.required = true;
                exportCountriesInput.required = true;
            }
        } else {
            exportConditionalFields.forEach(el => el.style.display = 'none');
            exportTurnoverInput.required = false;
            exportCountriesInput.required = false;
        }
    }
    isExporterSelector.addEventListener('change', toggleExportFields);

    // Toggle Other Textile Segment custom input
    function toggleOtherSegment() {
        if (!segmentSelector || !otherSegmentGroup) return;
        const isExpRole = roleSelector.value === 'exporter' || roleSelector.value === 'seller';
        if (segmentSelector.value === 'Other') {
            otherSegmentGroup.style.display = 'block';
            if (otherSegmentInput) {
                otherSegmentInput.required = isExpRole;
            }
        } else {
            otherSegmentGroup.style.display = 'none';
            if (otherSegmentInput) {
                otherSegmentInput.required = false;
            }
        }
    }
    if (segmentSelector) {
        segmentSelector.addEventListener('change', toggleOtherSegment);
        toggleOtherSegment();
    }

    // Helper to enable/disable section form controls so inactive sections never submit or fail constraint validation
    function setSectionInputsDisabled(container, disabled) {
        if (!container) return;
        const fields = container.querySelectorAll('input, select, textarea');
        fields.forEach(el => {
            el.disabled = disabled;
        });
    }

    function toggleBuyerFieldsRequired(required) {
        if (buyerCompanyName) buyerCompanyName.required = required;
        if (buyerAddress) buyerAddress.required = required;
        if (required) {
            if (primaryDetailsTitle) primaryDetailsTitle.innerHTML = '👤 Sourcing Contact / Representative Details';
            if (nameLabel) nameLabel.innerHTML = 'Contact Person / Representative Name <span style="color:#c62828">*</span>';
            if (nameHelp) nameHelp.textContent = 'Authorized sourcing contact / representative';
            if (phoneLabel) phoneLabel.innerHTML = 'Contact Mobile / Phone No <span style="color:#c62828">*</span>';
            if (phoneInput) phoneInput.placeholder = 'e.g. +1 212 555 0199 or +91 98765 43210';
            if (designationGroup) designationGroup.style.display = 'none';
            if (designationInput) {
                designationInput.required = false;
                designationInput.disabled = true;
            }
        }
    }

    function toggleExporterFieldsRequired(required) {
        if (gstInput) gstInput.required = required;
        if (companyNameInput) companyNameInput.required = required;
        if (phoneInput) phoneInput.required = required;
        if (yearInput) yearInput.required = required;
        if (factoryAddressInput) factoryAddressInput.required = required;
        if (stateSelector) stateSelector.required = required;
        if (districtSelector) districtSelector.required = required;
        if (segmentSelector) segmentSelector.required = required;
        if (hsnInput) hsnInput.required = required;
        if (capacityInput) capacityInput.required = required;
        if (capacityUnitSelector) capacityUnitSelector.required = required;
        if (monthlyProductionInput) monthlyProductionInput.required = required;
        if (machineriesInput) machineriesInput.required = required;
        if (turnoverSelector) turnoverSelector.required = required;
        if (isExporterSelector) isExporterSelector.required = required;
        if (authorityDeclarationCheckbox) authorityDeclarationCheckbox.required = required;
        
        if (required) {
            if (primaryDetailsTitle) primaryDetailsTitle.innerHTML = '👤 Higher Authority / Authorized Signatory Details';
            if (nameLabel) nameLabel.innerHTML = 'Authorized Signatory / Higher Authority Name <span style="color:#c62828">*</span>';
            if (nameHelp) nameHelp.textContent = 'Managing Director, Director, Partner, Proprietor, or Authorized Officer';
            if (phoneLabel) phoneLabel.innerHTML = 'Official Mobile No <span style="color:#c62828">*</span>';
            if (phoneInput) phoneInput.placeholder = 'e.g. +91 98765 43210';
            if (designationGroup) designationGroup.style.display = 'block';
            if (designationInput) {
                designationInput.required = true;
                designationInput.disabled = false;
            }
        }

        toggleExportFields();
        toggleOtherSegment();
        
        if (certificateFile) certificateFile.required = false;
        if (productImagesFile) productImagesFile.required = false;
    }

    // Toggle Exporter/Buyer Sections
    function applyRoleState() {
        const isExpRole = roleSelector.value === 'exporter' || roleSelector.value === 'seller';
        if (isExpRole) {
            sellerSection.style.display = 'block';
            sellerSection.classList.add('show');
            buyerSection.style.display = 'none';
            buyerSection.classList.remove('show');

            setSectionInputsDisabled(sellerSection, false);
            setSectionInputsDisabled(buyerSection, true);

            toggleExporterFieldsRequired(true);
            toggleBuyerFieldsRequired(false);
        } else {
            sellerSection.style.display = 'none';
            sellerSection.classList.remove('show');
            buyerSection.style.display = 'block';
            buyerSection.classList.add('show');

            setSectionInputsDisabled(sellerSection, true);
            setSectionInputsDisabled(buyerSection, false);

            toggleExporterFieldsRequired(false);
            toggleBuyerFieldsRequired(true);
        }
    }

    roleSelector.addEventListener('change', applyRoleState);
    applyRoleState();

    // File upload feedback
    if (certificateFile) {
        certificateFile.addEventListener('change', function () {
            if (uploadText) {
                if (certificateFile.files.length > 0) {
                    uploadText.innerText = "✓ Selected Business Proof (" + certificateFile.files[0].name + ")";
                    uploadText.style.color = "#2e7d32";
                } else {
                    uploadText.innerText = "📁 Upload Registration Proof";
                    uploadText.style.color = "var(--primary)";
                }
            }
        });
    }

    if (productImagesFile) {
        productImagesFile.addEventListener('change', function () {
            if (productImagesPreview) productImagesPreview.innerHTML = "";
            if (productImagesFile.files.length > 0) {
                if (productImagesText) {
                    productImagesText.innerText = `✓ Selected ${productImagesFile.files.length} Product Image(s)`;
                    productImagesText.style.color = "#2e7d32";
                }
                
                if (productImagesPreview) {
                    Array.from(productImagesFile.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.style.width = '60px';
                            img.style.height = '60px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '8px';
                            img.style.border = '1px solid var(--border)';
                            productImagesPreview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            } else if (productImagesText) {
                productImagesText.innerText = "📷 Upload Product Images (Multiple allowed)";
                productImagesText.style.color = "var(--primary)";
            }
        });
    }

    const applicantNameInput = document.querySelector('input[name="name"]');
    const applicantEmailInput = document.querySelector('input[name="email"]');
    const btnFetchGst = document.getElementById('btnFetchGst');
    const btnFetchGstIcon = document.getElementById('btnFetchGstIcon');
    const btnFetchGstText = document.getElementById('btnFetchGstText');
    const btnResetGst = document.getElementById('btnResetGst');
    const gstCharCounter = document.getElementById('gstCharCounter');
    const gstVerificationCard = document.getElementById('gstVerificationCard');
    const gstLiveBadge = document.getElementById('gstLiveBadge');
    const gstRecordStatus = document.getElementById('gstRecordStatus');
    const gstLegalNameDisplay = document.getElementById('gstLegalNameDisplay');
    const gstTradeNameDisplay = document.getElementById('gstTradeNameDisplay');
    const gstConstitutionDisplay = document.getElementById('gstConstitutionDisplay');
    const gstSignatoriesDisplay = document.getElementById('gstSignatoriesDisplay');
    const gstOwnershipBanner = document.getElementById('gstOwnershipBanner');

    // Audit card check elements
    const auditNameBadge = document.getElementById('auditNameBadge');
    const auditNameText = document.getElementById('auditNameText');
    const auditDesignationBadge = document.getElementById('auditDesignationBadge');
    const auditDesignationText = document.getElementById('auditDesignationText');
    const auditDomainBadge = document.getElementById('auditDomainBadge');
    const auditDomainText = document.getElementById('auditDomainText');

    // Lock badges
    const companyNameLockBadge = document.getElementById('companyNameLockBadge');
    const yearLockBadge = document.getElementById('yearLockBadge');
    const factoryAddressLockBadge = document.getElementById('factoryAddressLockBadge');
    const clusterLockBadge = document.getElementById('clusterLockBadge');

    // Statutory Declaration elements
    const authorityDeclarationCheckbox = document.getElementById('authorityDeclarationCheckbox');
    const declarantName = document.getElementById('declarantName');
    const declarantDesignation = document.getElementById('declarantDesignation');
    const declarantCompany = document.getElementById('declarantCompany');
    const declarantGstin = document.getElementById('declarantGstin');

    let currentGstData = null;

    // Dynamically update Statutory Undertaking text
    function updateStatutoryDeclaration() {
        const nameVal = applicantNameInput ? applicantNameInput.value.trim() : '';
        const desigVal = designationInput ? designationInput.value.trim() : '';
        const compVal = companyNameInput ? companyNameInput.value.trim() : '';
        const gstVal = gstInput ? gstInput.value.trim().toUpperCase() : '';

        if (declarantName) declarantName.textContent = nameVal || '[Authorized Signatory]';
        if (declarantDesignation) declarantDesignation.textContent = desigVal || '[Higher Authority]';
        if (declarantCompany) declarantCompany.textContent = compVal || '[Enterprise]';
        if (declarantGstin) declarantGstin.textContent = gstVal || '[GSTIN]';
    }

    // Lock prefilled GST fields so users cannot tamper with official taxpayer records
    function lockGstPrefilledFields(data) {
        if (companyNameInput) {
            companyNameInput.readOnly = true;
            companyNameInput.classList.add('input-locked-gst');
            if (companyNameLockBadge) companyNameLockBadge.style.display = 'inline-flex';
        }
        if (factoryAddressInput) {
            factoryAddressInput.readOnly = true;
            factoryAddressInput.classList.add('input-locked-gst');
            if (factoryAddressLockBadge) factoryAddressLockBadge.style.display = 'inline-flex';
        }
        if (data && data.year_of_establishment && yearInput) {
            yearInput.readOnly = true;
            yearInput.classList.add('input-locked-gst');
            if (yearLockBadge) yearLockBadge.style.display = 'inline-flex';
        }
        if (stateSelector) {
            stateSelector.style.pointerEvents = 'none';
            stateSelector.tabIndex = -1;
            stateSelector.classList.add('input-locked-gst');
        }
        // District / Taluk selector is kept editable as requested
        if (districtSelector) {
            districtSelector.style.pointerEvents = 'auto';
            districtSelector.tabIndex = 0;
            districtSelector.classList.remove('input-locked-gst');
        }
        if (clusterLockBadge) clusterLockBadge.style.display = 'none';
        if (btnResetGst) btnResetGst.style.display = 'inline-flex';
    }

    // Unlock prefilled GST fields when GSTIN is cleared or edited
    function unlockGstPrefilledFields() {
        if (companyNameInput) {
            companyNameInput.readOnly = false;
            companyNameInput.classList.remove('input-locked-gst');
            if (companyNameLockBadge) companyNameLockBadge.style.display = 'none';
        }
        if (factoryAddressInput) {
            factoryAddressInput.readOnly = false;
            factoryAddressInput.classList.remove('input-locked-gst');
            if (factoryAddressLockBadge) factoryAddressLockBadge.style.display = 'none';
        }
        if (yearInput) {
            yearInput.readOnly = false;
            yearInput.classList.remove('input-locked-gst');
            if (yearLockBadge) yearLockBadge.style.display = 'none';
        }
        if (stateSelector) {
            stateSelector.style.pointerEvents = 'auto';
            stateSelector.tabIndex = 0;
            stateSelector.classList.remove('input-locked-gst');
        }
        if (districtSelector) {
            districtSelector.style.pointerEvents = 'auto';
            districtSelector.tabIndex = 0;
            districtSelector.classList.remove('input-locked-gst');
        }
        if (clusterLockBadge) clusterLockBadge.style.display = 'none';
        if (btnResetGst) btnResetGst.style.display = 'none';
    }

    // Re-check ownership whenever applicant changes name, designation, or email
    [applicantNameInput, designationInput, applicantEmailInput].forEach(elem => {
        if (!elem) return;
        elem.addEventListener('input', function () {
            updateStatutoryDeclaration();
        });
        elem.addEventListener('change', function () {
            updateStatutoryDeclaration();
            const gstVal = gstInput.value.trim().toUpperCase();
            if (gstVal.length === 15) {
                triggerGstLookup(gstVal, false);
            }
        });
    });

    // Reset / Change GSTIN button listener
    if (btnResetGst) {
        btnResetGst.addEventListener('click', function () {
            gstInput.value = '';
            if (companyNameInput) companyNameInput.value = '';
            if (factoryAddressInput) factoryAddressInput.value = '';
            if (yearInput) yearInput.value = '';
            clearGstValidation();
            updateStatutoryDeclaration();
            gstInput.focus();
        });
    }

    // GST Input Format Auto-uppercase and Validation Trigger
    gstInput.addEventListener('input', function () {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        const val = this.value;

        if (gstCharCounter) {
            if (val.length === 0) {
                gstCharCounter.innerText = "Enter 15-character GSTIN. Taxpayer legal name, address & cluster will auto-fill from official Swipe GST records.";
                gstCharCounter.style.color = "var(--muted)";
            } else if (val.length < 15) {
                gstCharCounter.innerText = `${val.length} / 15 characters entered. (Need ${15 - val.length} more)`;
                gstCharCounter.style.color = "#d97706";
            } else {
                gstCharCounter.innerText = "✓ 15 characters entered. Ready to fetch official records.";
                gstCharCounter.style.color = "#16a34a";
            }
        }

        if (val.length === 15) {
            triggerGstLookup(val);
        } else {
            clearGstValidation();
        }
    });

    // Enter key triggers fetch instead of submitting form
    gstInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = gstInput.value.trim().toUpperCase();
            if (val.length === 15) {
                triggerGstLookup(val);
            } else {
                showGstError(`Incomplete GSTIN: 15 characters required (currently ${val.length} entered).`);
            }
        }
    });

    if (btnFetchGst) {
        btnFetchGst.addEventListener('click', function () {
            const val = gstInput.value.trim().toUpperCase();
            if (!val) {
                showGstError("Please enter a 15-character GSTIN number.");
                gstInput.focus();
                return;
            }
            if (val.length !== 15) {
                showGstError(`Incomplete GSTIN: 15 characters required (currently ${val.length} entered).`);
                gstInput.focus();
                return;
            }
            triggerGstLookup(val);
        });
    }

    function triggerGstLookup(gstNo, showFetchingStatus = true) {
        if (btnFetchGst && showFetchingStatus) {
            btnFetchGst.disabled = true;
            btnFetchGst.style.opacity = "0.7";
            if (btnFetchGstIcon) btnFetchGstIcon.className = "fas fa-spinner fa-spin";
            if (btnFetchGstText) btnFetchGstText.innerText = "Fetching...";
        }

        if (showFetchingStatus) {
            gstStatus.className = "verification-status";
            gstStatus.style.display = "block";
            gstStatus.style.color = "#2563eb";
            gstStatus.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i> Querying official tax registry for <strong>${gstNo}</strong>...`;
            if (gstCharCounter) gstCharCounter.style.display = "none";
        }

        const applicantName = applicantNameInput ? encodeURIComponent(applicantNameInput.value.trim()) : '';
        const applicantEmail = applicantEmailInput ? encodeURIComponent(applicantEmailInput.value.trim()) : '';
        const applicantDesignation = designationInput ? encodeURIComponent(designationInput.value.trim()) : '';

        const lookupEndpoint = "{{ url('/api/gst-lookup') }}".replace(/^https?:\/\/[^\/]+/, window.location.origin);
        const url = `${lookupEndpoint}?gst_no=${encodeURIComponent(gstNo)}&applicant_name=${applicantName}&applicant_email=${applicantEmail}&applicant_designation=${applicantDesignation}`;

        fetch(url)
            .then(response => {
                return response.json().then(data => {
                    if (!response.ok) {
                        throw new Error(data.message || ("GST lookup failed with HTTP " + response.status));
                    }
                    return data;
                });
            })
            .then(res => {
                resetFetchButton(true);
                if (res.success && res.data) {
                    const d = res.data;
                    currentGstData = d;
                    
                    gstStatus.className = "verification-status success";
                    gstStatus.style.display = "block";
                    gstStatus.style.color = "#15803d";
                    gstStatus.innerHTML = `<i class="fas fa-check-circle me-1"></i> Active Tax Registry Record: <strong>${d.trade_name || d.company_name || d.legal_name}</strong>`;
                    if (gstCharCounter) gstCharCounter.style.display = "none";
                    
                    companyNameInput.value = d.trade_name || d.company_name || d.legal_name || '';
                    if (d.address) factoryAddressInput.value = d.address;
                    if (d.year_of_establishment && yearInput) yearInput.value = d.year_of_establishment;
                    if (!phoneInput.value && d.contact_phone) phoneInput.value = d.contact_phone;
                    if (!websiteInput.value && d.website) websiteInput.value = d.website;

                    // Match State and District to TEFC cluster
                    const gstState = d.state || '';
                    const gstCity = d.city || '';

                    let matchedStateKey = null;
                    Object.keys(eligibleDistricts).forEach(stateKey => {
                        if (stateKey.toLowerCase() === gstState.toLowerCase()) {
                            matchedStateKey = stateKey;
                        }
                    });

                    if (matchedStateKey) {
                        stateSelector.value = matchedStateKey;
                        populateDistricts(matchedStateKey, gstCity);
                    } else if (gstState) {
                        districtWarning.style.display = 'block';
                        districtWarning.textContent = `Notice: Unit location (${gstCity}, ${gstState}) is outside operational TEFC Centre clusters. Facilitation is limited to Surat, Karur, Ichalkaranji, Jaipur, Varanasi, and Ludhiana.`;
                    }

                    // Lock prefilled fields so user cannot tamper with official taxpayer records
                    lockGstPrefilledFields(d);

                    // Update Statutory Declaration text
                    updateStatutoryDeclaration();

                    // Populate Card Details
                    if (gstVerificationCard) {
                        gstVerificationCard.style.display = 'block';
                        gstLiveBadge.innerText = res.is_live ? "Official Live Swipe API" : "Simulated Testing Mode";
                        gstLiveBadge.style.background = res.is_live ? "#dcfce7" : "#fef3c7";
                        gstLiveBadge.style.color = res.is_live ? "#166534" : "#92400e";

                        const isStatusActive = (d.status || 'Active').toLowerCase() === 'active';
                        gstRecordStatus.innerText = d.status || 'Active';
                        gstRecordStatus.className = isStatusActive ? "badge bg-success" : "badge bg-danger";

                        gstLegalNameDisplay.innerText = d.legal_name || d.company_name || 'N/A';
                        gstTradeNameDisplay.innerText = d.trade_name || d.company_name || 'N/A';
                        gstConstitutionDisplay.innerText = d.constitution || 'N/A';
                        
                        // Humanoid handling of signatories: Filter out corporate legal names
                        const cleanSignatories = Array.isArray(d.signatories) ? d.signatories.filter(s => {
                            const str = (s || '').trim().toLowerCase();
                            const lgnm = (d.legal_name || '').trim().toLowerCase();
                            const trad = (d.trade_name || '').trim().toLowerCase();
                            return str !== '' && str !== lgnm && str !== trad;
                        }) : [];

                        if (cleanSignatories.length > 0) {
                            gstSignatoriesDisplay.innerText = cleanSignatories.join(', ');
                        } else if ((d.constitution || '').toLowerCase().includes('proprietor')) {
                            gstSignatoriesDisplay.innerText = d.legal_name || 'Individual Proprietor';
                        } else {
                            gstSignatoriesDisplay.innerHTML = '<span style="color: #64748b; font-style: italic;">Directors / Signatories per Form GST REG-06 (Annexure B)</span>';
                        }

                        // Render Higher Authority ⇄ Enterprise Cross-Authentication Matrix
                        renderOwnershipBanner(res.ownership, applicantNameInput.value.trim(), d);
                    }
                } else {
                    showGstError(res.message || "GSTIN not found in registries.");
                }
            })
            .catch(err => {
                resetFetchButton(false);
                showGstError(err.message || "GST Verification failed. Please check format.");
            });
    }

    function renderOwnershipBanner(ownership, applicantName, d) {
        if (!gstVerificationCard) return;

        const desigVal = designationInput ? designationInput.value.trim() : '';
        const emailVal = applicantEmailInput ? applicantEmailInput.value.trim() : '';
        const entityName = d.trade_name || d.company_name || d.legal_name;

        // 1. Audit Check: Signatory Name Match
        if (!applicantName) {
            if (auditNameBadge) {
                auditNameBadge.className = 'badge bg-secondary';
                auditNameBadge.style = '';
                auditNameBadge.innerText = 'Pending Name';
            }
            if (auditNameText) {
                auditNameText.innerHTML = `Enter your full name in the <em>Authorized Signatory Name</em> field above to link your authority to this enterprise.`;
            }
        } else if (ownership && ownership.status === 'verified') {
            if (auditNameBadge) {
                auditNameBadge.className = 'badge bg-success';
                auditNameBadge.style = '';
                auditNameBadge.innerText = `Verified Signatory (${ownership.score}%)`;
            }
            if (auditNameText) {
                auditNameText.innerHTML = `<strong>✓ Official Match:</strong> "${applicantName}" matches registered ${ownership.matched_source}: <em>"${ownership.matched_signatory}"</em>.`;
            }
        } else if (ownership && ownership.status === 'pending_reg06_audit') {
            if (auditNameBadge) {
                auditNameBadge.className = 'badge';
                auditNameBadge.style = 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-weight: 700;';
                auditNameBadge.innerText = 'REG-06 Audit Required';
            }
            if (auditNameText) {
                auditNameText.innerHTML = `<strong>📋 Verification Pending REG-06 Audit:</strong> Applicant "<strong>${applicantName}</strong>" is applying as <strong>${desigVal || 'Authorized Signatory'}</strong> on behalf of <strong>${entityName}</strong>. Because director and partner appointments for incorporated companies are not published in public GST registry lookups, your authority is unverified and must be verified by your Regional TEFC Centre Officer using your uploaded Form GST REG-06 (Annexure B) / Board Resolution before approval.`;
            }
        } else if (ownership && ownership.status === 'partial_match') {
            if (auditNameBadge) {
                auditNameBadge.className = 'badge bg-warning text-dark';
                auditNameBadge.style = '';
                auditNameBadge.innerText = `Partial (${ownership.score}%)`;
            }
            if (auditNameText) {
                auditNameText.innerHTML = `<strong>⚠️ Partial Match:</strong> Matches <em>"${ownership.matched_signatory}"</em>. Please ensure Form GST REG-06 is attached.`;
            }
        } else {
            // Mismatch
            if (auditNameBadge) {
                auditNameBadge.className = 'badge bg-danger text-white';
                auditNameBadge.style = '';
                auditNameBadge.innerText = 'Signatory Mismatch';
            }
            if (auditNameText) {
                const onRecord = (ownership && ownership.matched_signatory) ? ownership.matched_signatory : ((d.signatories && d.signatories.length) ? d.signatories.join(', ') : d.legal_name);
                auditNameText.innerHTML = `<strong>⚠️ Mismatch with Official Record:</strong> Registered taxpayer on record is <em>"${onRecord}"</em>. You entered "<strong>${applicantName}</strong>". An official Authorization Letter or Form GST REG-06 is required.`;
            }
        }

        // 2. Audit Check: Constitution vs Designation Alignment
        if (!desigVal) {
            if (auditDesignationBadge) {
                auditDesignationBadge.className = 'badge bg-secondary';
                auditDesignationBadge.innerText = 'Pending Role';
            }
            if (auditDesignationText) {
                auditDesignationText.innerText = 'Select your Higher Authority Designation above to verify alignment with business structure.';
            }
        } else if (ownership && ownership.designation_check) {
            const desigCheck = ownership.designation_check;
            if (desigCheck.aligned) {
                if (auditDesignationBadge) {
                    auditDesignationBadge.className = 'badge bg-success';
                    auditDesignationBadge.innerText = 'Compatible Role';
                }
                if (auditDesignationText) {
                    auditDesignationText.innerHTML = `<strong>✓ Compatible Role:</strong> ${desigCheck.message}`;
                }
            } else {
                if (auditDesignationBadge) {
                    auditDesignationBadge.className = 'badge bg-warning text-dark';
                    auditDesignationBadge.innerText = 'Notice';
                }
                if (auditDesignationText) {
                    auditDesignationText.innerHTML = `<strong>⚠️ Structure Notice:</strong> ${desigCheck.message}`;
                }
            }
        }

        // 3. Audit Check: Email Domain Identity
        if (!emailVal) {
            if (auditDomainBadge) {
                auditDomainBadge.className = 'badge bg-secondary';
                auditDomainBadge.innerText = 'Pending Email';
            }
            if (auditDomainText) {
                auditDomainText.innerText = 'Enter your official email ID above to check domain verification.';
            }
        } else if (ownership && ownership.domain_check) {
            const domainCheck = ownership.domain_check;
            if (domainCheck.domain_match) {
                if (auditDomainBadge) {
                    auditDomainBadge.className = 'badge bg-success';
                    auditDomainBadge.innerText = 'Domain Matched';
                }
                if (auditDomainText) {
                    auditDomainText.innerHTML = `<strong>✓ Corporate Email:</strong> Domain (@${domainCheck.domain}) directly matches company identity.`;
                }
            } else if (domainCheck.is_corporate) {
                if (auditDomainBadge) {
                    auditDomainBadge.className = 'badge bg-info text-dark';
                    auditDomainBadge.innerText = 'Corporate Domain';
                }
                if (auditDomainText) {
                    auditDomainText.innerHTML = `Corporate domain (@${domainCheck.domain}) detected. Verified during Regional Centre audit.`;
                }
            } else {
                if (auditDomainBadge) {
                    auditDomainBadge.className = 'badge bg-light text-dark border';
                    auditDomainBadge.innerText = 'Standard Webmail';
                }
                if (auditDomainText) {
                    auditDomainText.innerHTML = `Using webmail (@${domainCheck.domain}). Authorized signatory identity will be verified via uploaded Form GST REG-06.`;
                }
            }
        }

        // Overall Guidance Banner
        if (gstOwnershipBanner) {
            if (!applicantName) {
                gstOwnershipBanner.style.background = "#eff6ff";
                gstOwnershipBanner.style.border = "1px solid #bfdbfe";
                gstOwnershipBanner.innerHTML = `
                    <div style="font-size: 1.1rem; color: #2563eb;">ℹ️</div>
                    <div style="color: #1e40af;">
                        <strong>Authority Details:</strong> Enter your Higher Authority Name and Designation above to link your profile to <strong>${d.legal_name}</strong>.
                    </div>
                `;
            } else if (ownership && ownership.status === 'verified') {
                gstOwnershipBanner.style.background = "#f0fdf4";
                gstOwnershipBanner.style.border = "1.5px solid #bbf7d0";
                gstOwnershipBanner.innerHTML = `
                    <div style="font-size: 1.25rem; color: #16a34a;">🛡️</div>
                    <div style="color: #166534; line-height: 1.45;">
                        <strong style="font-size: 0.95rem;">✓ Official Authority Verified:</strong><br>
                        Applicant "<strong>${applicantName}</strong>" matches the official registered signatory on record for <strong>${entityName}</strong>. Official taxpayer records are verified and locked.
                    </div>
                `;
            } else if (ownership && ownership.status === 'pending_reg06_audit') {
                gstOwnershipBanner.style.background = "#fffbeb";
                gstOwnershipBanner.style.border = "1.5px solid #fde68a";
                gstOwnershipBanner.innerHTML = `
                    <div style="font-size: 1.25rem; color: #d97706;">📋</div>
                    <div style="color: #92400e; line-height: 1.48;">
                        <strong style="font-size: 0.95rem;">Authority Verification Pending (REG-06 Document Audit Required):</strong><br>
                        You are registering as <strong>${desigVal || 'Authorized Signatory'}</strong> on behalf of <strong>${entityName}</strong>.<br>
                        <span style="font-size: 0.84rem; opacity: 0.95; display: inline-block; margin-top: 0.25rem;">
                            Since individual director/partner appointments are not listed on open GST registry lookups, your authority is <strong>unverified</strong>. Uploading your official <strong>Form GST REG-06 (Annexure B) or Board Resolution</strong> is mandatory so your Regional TEFC Centre Officer can inspect and authenticate your appointment before approving this enterprise account.
                        </span>
                    </div>
                `;
            } else if (ownership && ownership.status === 'partial_match') {
                gstOwnershipBanner.style.background = "#fffbeb";
                gstOwnershipBanner.style.border = "1.5px solid #fde68a";
                gstOwnershipBanner.innerHTML = `
                    <div style="font-size: 1.25rem; color: #d97706;">⚠️</div>
                    <div style="color: #92400e; line-height: 1.48;">
                        <strong style="font-size: 0.95rem;">Partial Signatory Match (${ownership.score}%):</strong><br>
                        Applicant "${applicantName}" closely resembles registered record <em>"${ownership.matched_signatory}"</em>.<br>
                        <span style="font-size: 0.84rem; opacity: 0.95;">
                            Your Regional TEFC Centre Officer will confirm the spelling match against your uploaded Form GST REG-06 prior to approval.
                        </span>
                    </div>
                `;
            } else {
                const onRecord = (ownership && ownership.matched_signatory) ? ownership.matched_signatory : ((d.signatories && d.signatories.length) ? d.signatories.join(', ') : d.legal_name);
                gstOwnershipBanner.style.background = "#fef2f2";
                gstOwnershipBanner.style.border = "1.5px solid #fecaca";
                gstOwnershipBanner.innerHTML = `
                    <div style="font-size: 1.25rem; color: #dc2626;">⚠️</div>
                    <div style="color: #991b1b; line-height: 1.48;">
                        <strong style="font-size: 0.95rem;">Signatory Mismatch Notice:</strong><br>
                        Applicant "<strong>${applicantName}</strong>" does not match the registered taxpayer name on record (<em>${onRecord}</em>).<br>
                        <span style="font-size: 0.84rem; opacity: 0.95; display: inline-block; margin-top: 0.25rem;">
                            If you are registering as a delegated manager or representative, you must upload an official <strong>Board Resolution / Form GST REG-06 (Annexure B) / Letter of Authority</strong>. Your Regional TEFC Centre Officer will manually audit your credentials prior to approval.
                        </span>
                    </div>
                `;
            }
        }
    }

    function resetFetchButton(isSuccess) {
        if (!btnFetchGst) return;
        btnFetchGst.disabled = false;
        btnFetchGst.style.opacity = "1";
        if (isSuccess) {
            btnFetchGst.style.background = "#15803d";
            if (btnFetchGstIcon) btnFetchGstIcon.className = "fas fa-check";
            if (btnFetchGstText) btnFetchGstText.innerText = "Loaded";
        } else {
            btnFetchGst.style.background = "var(--primary)";
            if (btnFetchGstIcon) btnFetchGstIcon.className = "fas fa-search";
            if (btnFetchGstText) btnFetchGstText.innerText = "Fetch Details";
        }
    }

    function showGstError(msg) {
        gstStatus.className = "verification-status error";
        gstStatus.style.display = "block";
        gstStatus.style.color = "#c62828";
        gstStatus.innerHTML = `<i class="fas fa-exclamation-circle me-1"></i> ${msg}`;
        if (gstCharCounter) gstCharCounter.style.display = "block";
        if (gstVerificationCard) gstVerificationCard.style.display = 'none';
        unlockGstPrefilledFields();
        resetFetchButton(false);
    }

    function clearGstValidation() {
        gstStatus.style.display = "none";
        if (gstCharCounter) gstCharCounter.style.display = "block";
        if (gstVerificationCard) gstVerificationCard.style.display = 'none';
        unlockGstPrefilledFields();
        resetFetchButton(false);
    }

    // IEC Validation Check & Auto-activate Exporter Status
    function handleIecChange() {
        if (!iecInput) return;
        iecInput.value = iecInput.value.replace(/[^0-9]/g, '');
        const iecVal = iecInput.value;
        
        if (iecVal.length === 10) {
            iecBadge.className = "input-status-badge success";
            iecBadge.innerText = "✓ Valid Format";
        } else if (iecVal.length > 0) {
            iecBadge.className = "input-status-badge error";
            iecBadge.innerText = "✗ Must be 10 digits";
        } else {
            iecBadge.className = "input-status-badge";
            iecBadge.innerText = "";
        }

        // If user entered IEC, automatically switch "Are you an Exporter?" to "Yes"
        if (iecVal.length > 0 && isExporterSelector) {
            if (isExporterSelector.value !== 'Y') {
                isExporterSelector.value = 'Y';
                toggleExportFields();
            }
        }
    }

    if (iecInput) {
        iecInput.addEventListener('input', handleIecChange);
        iecInput.addEventListener('change', handleIecChange);
        if (iecInput.value.trim().length > 0) {
            handleIecChange();
        }
    }

    // Form Submission checks
    registerForm.addEventListener('submit', function (e) {
        const isExpRole = roleSelector.value === 'exporter' || roleSelector.value === 'seller';
        if (isExpRole) {
            const gstVal = gstInput ? gstInput.value.trim().toUpperCase() : '';
            const iecVal = iecInput ? iecInput.value.trim() : '';
            
            const gstPattern = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{3}$/;
            if (!gstPattern.test(gstVal)) {
                e.preventDefault();
                alert("Please enter a valid 15-character GSTIN number.");
                gstInput.focus();
                return;
            }

            // IEC is optional, but if entered it must be exactly 10 digits
            if (iecVal.length > 0 && iecVal.length !== 10) {
                e.preventDefault();
                alert("If providing an IEC (Import Export Code), it must be exactly 10 digits.");
                iecInput.focus();
                return;
            }

            if (!companyNameInput.value.trim()) {
                e.preventDefault();
                alert("Please complete the Company / Manufacturer Name.");
                companyNameInput.focus();
                return;
            }

            if (!factoryAddressInput.value.trim()) {
                e.preventDefault();
                alert("Please enter your Factory Address.");
                factoryAddressInput.focus();
                return;
            }

            if (!stateSelector.value) {
                e.preventDefault();
                alert("Please select your State.");
                stateSelector.focus();
                return;
            }

            if (!districtSelector.value) {
                e.preventDefault();
                alert("Please select your covered District within the TEFC Centre clusters.");
                districtSelector.focus();
                return;
            }

            if (segmentSelector && segmentSelector.value === 'Other') {
                if (otherSegmentInput && !otherSegmentInput.value.trim()) {
                    e.preventDefault();
                    alert("Please specify your Textile Segment in the input box provided.");
                    otherSegmentInput.focus();
                    return;
                }
            }

            if (certificateFile && certificateFile.files.length === 0) {
                e.preventDefault();
                alert("Please upload your Certificate of Registration / Business Proof (Form GST REG-06).");
                certificateFile.parentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            if (authorityDeclarationCheckbox && !authorityDeclarationCheckbox.checked) {
                e.preventDefault();
                alert("Please confirm the Statutory Undertaking & Authority Affirmation declaration before proceeding.");
                authorityDeclarationCheckbox.focus();
                return;
            }
        } else {
            // Buyer Role validation
            if (!buyerCompanyName.value.trim()) {
                e.preventDefault();
                alert("Please enter your Company / Organisation Name.");
                buyerCompanyName.focus();
                return;
            }

            if (!buyerAddress.value.trim()) {
                e.preventDefault();
                alert("Please enter your Business Address.");
                buyerAddress.focus();
                return;
            }
        }
    });
});
</script>
@endsection
