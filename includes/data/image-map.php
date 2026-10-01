<?php
/**
 * The photography plan for the whole site.
 *
 * Every image key used anywhere in the templates is declared here with the
 * Pexels search term it should be sourced from, the alt text it must carry,
 * and which local SVG to fall back to if Pexels is unreachable.
 *
 * Search terms are deliberately varied per page — reusing three stock photos
 * across twenty-four pages is exactly the look we are trying to avoid.
 *
 * Run `php tools/fetch-images.php` to resolve these into cached local files.
 */
return [

/* ------------------------------------------------------------ core pages */
/* NOTE: the home hero no longer draws from Pexels. It uses two client-supplied
   images (assets/img/hero-dr.png and hero-backdrop.jpg, produced by
   tools/make-hero-assets.php), so there is deliberately no 'home-hero' key
   here — leaving one would credit a photographer on /credits.php for a photo
   that is not on the site. */
'home-about' => [
    'query' => 'medical administrator working computer',
    'orientation' => 'landscape',
    'alt' => 'Billing administrator reviewing insurance claim data on a computer',
    'fallback' => 'office',
],
'home-why' => [
    'query' => 'business team analyzing financial charts',
    'orientation' => 'portrait',
    'alt' => 'Revenue cycle analysts reviewing collection performance charts',
    'fallback' => 'office',
],
'about-story' => [
    'query' => 'medical office team discussion',
    'orientation' => 'landscape',
    'alt' => 'Right Way Medical Billing team discussing a practice revenue cycle plan',
    'fallback' => 'team',
],
'about-compliance' => [
    'query' => 'data security server room',
    'orientation' => 'landscape',
    'alt' => 'Secure data infrastructure supporting HIPAA compliant medical billing',
    'fallback' => 'security',
],
'about-certified' => [
    'query' => 'professional training classroom adults',
    'orientation' => 'landscape',
    'alt' => 'Certified medical coders in a continuing education session',
    'fallback' => 'team',
],
'services-hub' => [
    'query' => 'business documents calculator desk finance',
    'orientation' => 'landscape',
    'alt' => 'Insurance claim paperwork and reports on a medical billing desk',
    'fallback' => 'office',
],
'specialties-hub' => [
    'query' => 'diverse doctors hospital corridor',
    'orientation' => 'landscape',
    'alt' => 'Physicians from a range of medical specialties in a hospital corridor',
    'fallback' => 'clinic',
],
'contact-hero' => [
    'query' => 'customer support headset office',
    'orientation' => 'landscape',
    'alt' => 'Client support specialist taking a call from a medical practice',
    'fallback' => 'office',
],

/* Aside panel of the home-page consultation booking block. */
'home-booking' => [
    'query' => 'medical team meeting bright office',
    'photo_id' => 5452228,
    'orientation' => 'portrait',
    'alt' => 'Practice team reviewing paperwork and revenue reports together',
    'fallback' => 'team',
],

/* ------------------------------------------------------- leadership team */
'team-1' => [
    'query' => 'professional woman portrait business',
    'orientation' => 'portrait',
    'alt' => 'Portrait of the Director of Client Success',
    'fallback' => 'portrait',
],
'team-2' => [
    'query' => 'professional man portrait office',
    'orientation' => 'portrait',
    'alt' => 'Portrait of the Lead Coding Specialist',
    'fallback' => 'portrait',
],
'team-3' => [
    'query' => 'confident businesswoman headshot',
    'orientation' => 'portrait',
    'alt' => 'Portrait of the Denial Management Supervisor',
    'fallback' => 'portrait',
],
'team-4' => [
    'query' => 'businessman headshot smiling',
    'orientation' => 'portrait',
    'alt' => 'Portrait of the Credentialing Manager',
    'fallback' => 'portrait',
],

/* ---------------------------------------------------------- testimonials */
'testimonial-1' => [
    'query' => 'doctor headshot woman face closeup',
    'orientation' => 'square',
    'alt' => 'Practice owner who works with Right Way Medical Billing',
    'fallback' => 'portrait',
],
'testimonial-2' => [
    'query' => 'man face portrait closeup professional',
    'orientation' => 'square',
    'alt' => 'Physician client of Right Way Medical Billing',
    'fallback' => 'portrait',
],
'testimonial-3' => [
    'query' => 'woman face headshot smiling closeup',
    'orientation' => 'square',
    'alt' => 'Practice manager who moved billing to Right Way Medical Billing',
    'fallback' => 'portrait',
],
'testimonial-4' => [
    'query' => 'older man face portrait closeup',
    'orientation' => 'square',
    'alt' => 'Senior physician describing results from outsourced medical billing',
    'fallback' => 'portrait',
],
'testimonial-5' => [
    'query' => 'young man face headshot closeup',
    'orientation' => 'square',
    'alt' => 'Dental practice owner discussing insurance billing results',
    'fallback' => 'portrait',
],

/* ------------------------------------------------------------- services */
'svc-medical-billing' => [
    'query' => 'accountant reviewing invoices office',
    'orientation' => 'landscape',
    'alt' => 'Biller reconciling claims and remittances for a medical practice',
    'fallback' => 'office',
],
'svc-medical-coding' => [
    'query' => 'medical records coding documents',
    'orientation' => 'landscape',
    'alt' => 'Certified coder assigning CPT and ICD-10 codes from a clinical record',
    'fallback' => 'office',
],
'svc-denial-management' => [
    'query' => 'business people reviewing documents meeting',
    'orientation' => 'landscape',
    'alt' => 'Denial management team preparing insurance claim appeals',
    'fallback' => 'office',
],
'svc-credentialing' => [
    'query' => 'signing contract paperwork office',
    'orientation' => 'landscape',
    'alt' => 'Provider credentialing application and payer contract being completed',
    'fallback' => 'office',
],
'svc-eligibility' => [
    'query' => 'medical receptionist front desk patient',
    'orientation' => 'landscape',
    'alt' => 'Front desk staff verifying a patient insurance eligibility at check-in',
    'fallback' => 'clinic',
],
'svc-patient-billing' => [
    'query' => 'person paying bill online laptop',
    'orientation' => 'landscape',
    'alt' => 'Patient paying a medical statement balance online',
    'fallback' => 'office',
],
'svc-oon-billing' => [
    'query' => 'business negotiation handshake meeting',
    'orientation' => 'landscape',
    'alt' => 'Out-of-network reimbursement negotiation between provider and payer',
    'fallback' => 'office',
],
'svc-mips' => [
    'query' => 'analytics dashboard performance data',
    'orientation' => 'landscape',
    'alt' => 'MIPS quality performance dashboard showing measure scores',
    'fallback' => 'office',
],

/* ---------------------------------------------------------- specialties */
'spec-gastroenterology' => [
    'query' => 'endoscopy procedure room hospital',
    'orientation' => 'landscape',
    'alt' => 'Endoscopy suite where gastroenterology procedures are performed',
    'fallback' => 'clinic',
],
'spec-pediatric' => [
    'query' => 'pediatrician examining child patient',
    'orientation' => 'landscape',
    'alt' => 'Pediatrician examining a young child during a well-child visit',
    'fallback' => 'clinic',
],
'spec-nephrology' => [
    'query' => 'hospital patient intravenous treatment bed',
    'orientation' => 'landscape',
    'alt' => 'Patient receiving intravenous treatment during a nephrology care admission',
    'fallback' => 'clinic',
],
'spec-family-practice' => [
    'query' => 'family doctor consultation patient',
    'orientation' => 'landscape',
    'alt' => 'Family physician consulting with a patient in a primary care clinic',
    'fallback' => 'clinic',
],
'spec-dental' => [
    'query' => 'dental clinic dentist patient',
    'orientation' => 'landscape',
    'alt' => 'Dentist treating a patient in a modern dental clinic',
    'fallback' => 'clinic',
],
'spec-hematology' => [
    'query' => 'laboratory blood test analysis',
    'orientation' => 'landscape',
    'alt' => 'Laboratory analysis supporting hematology and oncology treatment',
    'fallback' => 'clinic',
],
'spec-ob-gyn' => [
    'query' => 'pregnant woman ultrasound doctor',
    'orientation' => 'landscape',
    'alt' => 'Obstetric ultrasound appointment during prenatal care',
    'fallback' => 'clinic',
],
'spec-chiropractic' => [
    'query' => 'chiropractor treating patient back',
    'orientation' => 'landscape',
    'alt' => 'Chiropractor performing a spinal adjustment on a patient',
    'fallback' => 'clinic',
],
'spec-podiatry' => [
    'query' => 'podiatrist foot treatment clinic',
    'orientation' => 'landscape',
    'alt' => 'Podiatrist examining a patient foot during a clinic visit',
    'fallback' => 'clinic',
],
'spec-physical-therapy' => [
    'query' => 'physical therapist rehabilitation exercise',
    'orientation' => 'landscape',
    'alt' => 'Physical therapist guiding a patient through a rehabilitation exercise',
    'fallback' => 'clinic',
],
'spec-behavioral-health' => [
    'query' => 'therapy session counseling talking',
    'orientation' => 'landscape',
    'alt' => 'Behavioral health counselling session between therapist and client',
    'fallback' => 'clinic',
],
'spec-internal-medicine' => [
    'query' => 'doctor consulting elderly patient',
    'orientation' => 'landscape',
    'alt' => 'Internist reviewing chronic conditions with an older adult patient',
    'fallback' => 'clinic',
],
'spec-infectious-disease' => [
    'query' => 'microbiology laboratory scientist',
    'orientation' => 'landscape',
    'alt' => 'Microbiology laboratory work supporting infectious disease treatment',
    'fallback' => 'clinic',
],
'spec-wound-care' => [
    'query' => 'nurse bandaging patient wound',
    'orientation' => 'landscape',
    'alt' => 'Clinician applying a dressing during a wound care treatment',
    'fallback' => 'clinic',
],
'spec-rehab' => [
    'query' => 'rehabilitation therapy walking assistance',
    'orientation' => 'landscape',
    'alt' => 'Rehabilitation therapist supporting a patient with mobility training',
    'fallback' => 'clinic',
],
'spec-urgent-care' => [
    'query' => 'urgent care clinic waiting room',
    'orientation' => 'landscape',
    'alt' => 'Urgent care clinic reception where walk-in patients are registered',
    'fallback' => 'clinic',
],

];
