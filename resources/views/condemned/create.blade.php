@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6 text-red-700">
        ➕ Add Condemned Equipment
    </h1>

    {{-- DEFINE LISTS HERE FOR EASY EDITING --}}
    @php
        $departments = [
            "Accounting Section - Finance Division", "Accounting Section, Finance Division - Dagupan Campus",
            "Accounting Section, Finance Division - Rizal Campus", "Administrative Council", "Administrative Services Division",
            "Agri-Based IGP - Cattle Production", "Agri-Based IGP - Egg Production", "Agri-Based IGP - Goat Production",
            "Agri-Based IGP - Rice Production", "Agri-Based IGP - Swine Production", "Alcohol Production",
            "Analytic Laboratory", "Breeding and Insimination Facility", "Budget Section - Finance Division",
            "Budget Section, Finance Division - Dagupan Campus", "Budget Section, Finance Division - Rizal Campus",
            "Business Affairs Office", "Business Affairs Office - Agri-Based IGP", "Business Affairs Office - Auxiliary Services",
            "Business Affairs Office - Non-Agri-Based IGP", "Business Affairs Office (BAO)",
            "Campus-Based Kalinga Cultural Heritage Studies & Edu-Tourism Centre", "Cashiering Section - Finance Division",
            "Cashiering Section, Finance Division - Dagupan Campus", "Cashiering Section, Finance Division - Rizal Campus",
            "Center for Training and Professional Development", "Center for Training and Professional Education",
            "Center for Watershed Management", "Central Science Laboratory Services Office", "Central Student Government",
            "Coffee Research Center", "College of Advanced Studies", "College of Advanced Studies-Extension",
            "College of Advanced Studies-Research", "College of Agriculture", "College of Agriculture - Extension and Training Services",
            "College of Agriculture - HEIRLOOM Corn Project", "College of Agriculture - Research and Development Services",
            "College of Agro-Forestry and Environmental Sciences", "College of Agro-Forestry and Environmental Sciences - Extension and Training Services",
            "College of Agro-Forestry and Environmental Sciences - Research and Development Services", "College of Agroforestry and Environmental Studies",
            "College of Business Administration and Accountancy", "College of Business Administration and Accountancy - Extension and Training Services",
            "College of Business Administration and Accountancy - Research and Development Services", "College of Criminal Justice Education",
            "College of Criminal Justice Education - Extension and Training Services", "College of Criminal Justice Education - Research and Development Services",
            "College of Education", "College of Education - Extension and Training Services", "College of Education - Research and Development Services",
            "College of Education and Information Technology", "College of Engineering and Information Technology",
            "College of Engineering and Information Technology - Computer Engineering Laboratory", "College of Engineering and Information Technology - Extension and Training Services",
            "College of Engineering and Information Technology - Research and Development Services", "College of Entrepreneurship, Tourism and Hospitality Management",
            "College of Entrepreneurship, Tourism and Hotel Management", "College of Entrepreneurship, Tourism and Hotel Management - Extension and Training Services",
            "College of Entrepreneurship, Tourism and Hotel Management - Research and Development Services", "College of Forestry",
            "College of Forestry - Extension and Training Services", "College of Forestry - Research and Development Services",
            "College of Health and Natural Sciences", "College of Health and Natural Sciences - Birthing Clinic",
            "College of Health and Natural Sciences - Extension and Training Services", "College of Health and Natural Sciences - Research and Development Services",
            "College of Law", "College of Law - Extension and Training Services", "College of Law - Research and Development Services",
            "College of Liberal Arts and Social Sciences", "College of Liberal Arts and Social Sciences - Extension and Training Services",
            "College of Liberal Arts and Social Sciences - Research and Development Services", "College of Public Administration and Indigenous Governance",
            "College of Public Administration and Indigenous Governance - Extension and Training Services",
            "College of Public Administration and Indigenous Governance - Research and Development Services", "Commission on Audit",
            "Counselling, Testing and Placement Services - Bulanao Campus", "Counselling, Testing and Placement Services - Dagupan Campus",
            "Counselling, Testing and Placement Services - Laboratory High School", "Counselling, Testing and Placement Services - Rizal Campus",
            "Dental Services Office", "Department of Information Technology", "Department of Languages", "Department of Mathematics",
            "Department of Natural Sciences", "Department of Physical Education", "Department of Social Sciences", "Finance / Accounting Office",
            "Finance Management Services Division", "Food Processing and Innovation Research Center", "Futures Thinking and Strategic Foresight",
            "Gender and Development", "General Service Office", "General Services Office", "General Services Office (GSO)",
            "Graduate School", "Guidance and Counseling", "Hotel and Restaurant Management (HRM) Hostel",
            "HRM Bldg. Compound - Water Resources Facility", "Human Resource Management Office - Bulanao Campus",
            "Human Resource Management Office - Dagupan Campus", "Human Resource Management Office - Rizal Campus",
            "Human Resource Management Office (HRMO)", "ICT Office", "Information and Communications Technology Center",
            "Internal Control Unit", "KALINGA DINAYAO RESEARCH CENTER", "Kalinga National High School Faculty",
            "KSU Center for International Languages", "KSU Gymnasium", "KSU-DOST CLEARS PROJECT", "KSU-DOST GIA (BOONDOCK)",
            "KSU-MAKILALA MINING Env'l Baseline Studies", "KSU-OFFICE OF PRESIDENTIAL ADVISER ON THE PEACE PROCESS",
            "KSU-TESDA Project", "Laboratory High School", "Laboratory High School - Extension and Training Services",
            "Laboratory High School - Research and Development Services", "LADIES DORMITORY-RIZAL CAMPUS", "Legal Unit",
            "Library", "Medical and Dental Clinic", "Medical Services Office", "Mushroom Production Facility-Bulanao Campus",
            "Mushroom Research Facility", "Office of the Alumni Affairs and Services", "Office of the Board/University Secretary",
            "Office of the Campus Administrator - Bulanao Campus", "Office of the Campus Administrator - Dagupan Campus",
            "Office of the Campus Administrator - Rizal Campus", "Office of the Income Generating Projects Services",
            "Office of the Library Services - Dagupan Campus", "Office of the Library Services - Laboratory High School",
            "Office of the Library Services - Main Campus", "Office of the Library Services - Rizal Campus",
            "Office of the NSTP Services", "Office of the Open Distance Education, Transnational Education and International Learning",
            "Office of the President", "Office of the Scholarship Services", "Office of the Sports and Socio-Cultural Affairs",
            "Office of the Student Development Services and Placement Services", "Office of the Supervisor for Special Projects",
            "Office of the University Extension and Training Services", "Office of the University President",
            "Office of the University Registrar", "Office of the University Research and Development",
            "Office of the Vice President for Academic Affairs", "Office of the Vice President for Academic and Student Development",
            "Office of the Vice President for Administration and Finance", "Office of the Vice President for Research and Development, Extension and Training",
            "Office of the Vice President for Research, Extension and Development", "Payroll Section - Finance Division",
            "Performing Arts Theater", "Planning and Strategy Office", "PMO- Bids and Award Committee", "Procurement Management Office",
            "Procurement Management Office - Dagupan Campus", "Procurement Management Office - Rizal Campus",
            "Project Management Unit", "Quality Assurance Office", "Records and Archives Office", "Records and Archives Section - Dagupan Campus",
            "Records and Archives Section - Rizal Campus", "Registrar", "Research and Development, Extension and Training Hostel",
            "Research and Extension Office", "Rizal Campus - Computer Laboratory", "Rizal Campus - Sericulture Research Program",
            "Security Services-Bulanao Campus", "Security Services-Dagupan Campus", "Security Services-Rizal Campus",
            "Sentro ng Wika at Kultura", "Student Internship Abroad Program (SIAP) Services", "Student Publication (EARTHLINE)",
            "Supply and Property Management Office - Bulanao Campus", "Supply and Property Management Office - Dagupan Campus",
            "Supply and Property Management Office - Rizal Campus", "Supply Procurement Office", "Supreme Student Council - Bulanao Campus",
            "Supreme Student Council - Dagupan Campus", "Supreme Student Council - Rizal Campus", "Technology and Innovation",
            "University Disaster Risk Reduction Management", "University Environmental Management Committee", "University Information Office - AWONG Publication"
        ];

        $categories = [
            'Hardware', 'Software', 'Network', 'Email & Accounts', 'Website & Online Services',
            'Printing & Scanning', 'Multimedia Equipment', 'Server & Storage', 'Security', 'Others'
        ];

        $it_personnel = ['Walid', 'Bryan', 'Jhune', 'Reymar'];
    @endphp

    {{-- ✅ ADDED enctype="multipart/form-data" HERE --}}
    <form action="{{ route('condemned-equipment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Property No --}}
            <div>
                <label class="block font-semibold mb-1">Property No</label>
                <input type="text" name="property_no" class="border rounded w-full p-2" required>
            </div>

            {{-- Item Name --}}
            <div>
                <label class="block font-semibold mb-1">Item Name</label>
                <input type="text" name="item_name" class="border rounded w-full p-2" required>
            </div>

            {{-- Title --}}
            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" class="border rounded w-full p-2" required>
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" rows="3" class="border rounded w-full p-2"></textarea>
            </div>

            {{-- ✅ ATTACHMENT / PROOF SECTION ADDED HERE --}}
            <div class="md:col-span-2 bg-gray-50 p-3 rounded border border-gray-200">
                <label class="block font-semibold mb-1">Attachment / Proof (Optional)</label>
                <input type="file" name="attachment" class="w-full p-1" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <p class="text-xs text-gray-500 mt-1">Allowed formats: JPG, PNG, PDF, DOC (Max 5MB)</p>
            </div>

            {{-- Equipment Type --}}
            <div>
                <label class="block font-semibold mb-1">Equipment Type</label>
                <input type="text" name="equipment_type" class="border rounded w-full p-2" required>
            </div>

            {{-- Brand / Model --}}
            <div>
                <label class="block font-semibold mb-1">Brand / Model</label>
                <input type="text" name="brand_model" class="border rounded w-full p-2">
            </div>

            {{-- Serial No --}}
            <div>
                <label class="block font-semibold mb-1">Serial No</label>
                <input type="text" name="serial_no" class="border rounded w-full p-2">
            </div>

            {{-- Category Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">Category</label>
                <select name="category" class="border rounded w-full p-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <select name="department" class="border rounded w-full p-2">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            {{-- IT Personnel Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">IT Personnel</label>
                <select name="it_personnel" class="border rounded w-full p-2">
                    <option value="">Select Personnel</option>
                    @foreach($it_personnel as $person)
                        <option value="{{ $person }}">{{ $person }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Client --}}
            <div>
                <label class="block font-semibold mb-1">Client</label>
                <input type="text" name="client_name" class="border rounded w-full p-2">
            </div>

            {{-- Priority --}}
            <div>
                <label class="block font-semibold mb-1">Priority</label>
                <select name="priority" class="border rounded w-full p-2">
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>
                </select>
            </div>

            {{-- Contact --}}
            <div>
                <label class="block font-semibold mb-1">Contact</label>
                <input type="text" name="contact" class="border rounded w-full p-2">
            </div>

            {{-- Status --}}
            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="border rounded w-full p-2">
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Finished">Finished</option>
                    <option value="Closed">Closed</option>
                    <option value="Condemned">Condemned</option>
                </select>
            </div>

            {{-- Date Submitted --}}
            <div>
                <label class="block font-semibold mb-1">Date Submitted</label>
                <input type="date" name="date_submitted" class="border rounded w-full p-2">
            </div>

            {{-- Date Condemned --}}
            <div>
                <label class="block font-semibold mb-1">Date Condemned</label>
                <input type="date" name="date_condemned" class="border rounded w-full p-2">
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded shadow">
                Save Condemned Equipment
            </button>
            <a href="{{ route('condemned-equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded shadow">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection