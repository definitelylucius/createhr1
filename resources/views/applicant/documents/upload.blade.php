<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Pre-Employment Documents</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto py-8 px-4">
    <!-- Back Button -->
    <div class="mb-6">
    <a href="/" class="inline-flex items-center text-blue-600 hover:text-blue-800">
    <!-- SVG icon -->
    Back to Dashboard
</a>

    <h1 class="text-2xl font-bold mb-6">Upload Pre-Employment Documents</h1>
    
    <!-- Document Requirements Section -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Required Documents</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <ul class="list-disc pl-5 space-y-1">
                        <li>All documents must be clear and legible</li>
                        <li>Files must be in PDF, JPG, or PNG format</li>
                        <li>Maximum file size: 2MB per document</li>
                        <li>Expired documents will not be accepted</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Success/Error Messages -->
    <div id="messageContainer" class="mb-4"></div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form id="documentUploadForm" method="POST" enctype="multipart/form-data" 
              action="/applicant/documents/{{ $applicationId }}/upload" class="space-y-4">
            @csrf
            
            <div>
                <label for="document_type" class="block text-gray-700 mb-2 font-medium">Document Type <span class="text-red-500">*</span></label>
                <select name="document_type" id="document_type" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Select Document Type</option>
                    <option value="nbi_clearance">NBI Clearance (Valid within 1 year)</option>
                    <option value="police_clearance">Police Clearance (Valid within 6 months)</option>
                    <option value="barangay_clearance">Barangay Clearance (Valid within 6 months)</option>
                    <option value="coe">Certificate of Employment (Current employer)</option>
                    <option value="drivers_license">Driver's License (Valid, not expired)</option>
                    <option value="drug_test">Drug Test Result (Valid within 3 months)</option>
                    <option value="medical_exam">Medical Exam Result (Valid within 6 months)</option>
                </select>
            </div>
            
            <div>
                <label for="document_file" class="block text-gray-700 mb-2 font-medium">Document File <span class="text-red-500">*</span></label>
                <input type="file" name="document_file" id="document_file" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                <p class="text-sm text-gray-500 mt-1">Accepted formats: PDF, JPG, PNG (Max: 2MB)</p>
            </div>
            
            <div class="flex justify-between items-center pt-4">
            <a href="/"  class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Upload Document
                </button>
            </div>
        </form>
    </div>
    
    <!-- List of already uploaded documents -->
    <div class="mt-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Your Uploaded Documents</h2>
        <div id="uploadedDocumentsList">
            <!-- This will be populated by JavaScript -->
            <p class="text-gray-500">Loading documents...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('documentUploadForm');
    const messageContainer = document.getElementById('messageContainer');
    
    // Load existing documents
    loadUploadedDocuments();
    
    // Handle form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showMessage('success', result.message || 'Document uploaded successfully!');
                form.reset();
                loadUploadedDocuments();
            } else {
                showMessage('error', result.message || 'Error uploading document');
            }
        } catch (error) {
            showMessage('error', 'Network error occurred. Please try again.');
        }
    });
    
    function showMessage(type, text) {
        messageContainer.innerHTML = `
            <div class="p-4 rounded-md ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                ${text}
            </div>
        `;
        
        setTimeout(() => {
            messageContainer.innerHTML = '';
        }, 5000);
    }
    
    async function loadUploadedDocuments() {
        try {
            const response = await fetch(`/applicant/documents/{{ $applicationId }}/list`);
            const documents = await response.json();
            
            const container = document.getElementById('uploadedDocumentsList');
            
            if (documents.length === 0) {
                container.innerHTML = '<p class="text-gray-500">No documents uploaded yet.</p>';
                return;
            }
            
            let html = '<div class="space-y-4">';
            
            documents.forEach(doc => {
                html += `
                    <div class="flex items-center justify-between border-b pb-2">
                        <div>
                            <span class="font-medium">${doc.name}</span>
                            <span class="text-sm text-gray-500 ml-2">
                                (Uploaded: ${new Date(doc.updated_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })})
                            </span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/storage/${doc.path}" 
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 hover:underline">
                                View
                            </a>
                            <button onclick="deleteDocument('${doc.type}', '${doc.path}')" 
                                    class="text-red-600 hover:text-red-800 hover:underline">
                                Delete
                            </button>
                        </div>
                    </div>
                `;
            });
            
            html += '</div>';
            container.innerHTML = html;
        } catch (error) {
            document.getElementById('uploadedDocumentsList').innerHTML = 
                '<p class="text-red-500">Error loading documents. Please refresh the page.</p>';
        }
    }
    
    window.deleteDocument = async function(type, path) {
        if (!confirm('Are you sure you want to delete this document?')) return;
        
        try {
            const response = await fetch(`/applicant/documents/{{ $applicationId }}/delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type, path })
            });
            
            const result = await response.json();
            
            if (response.ok) {
                showMessage('success', result.message || 'Document deleted successfully!');
                loadUploadedDocuments();
            } else {
                showMessage('error', result.message || 'Error deleting document');
            }
        } catch (error) {
            showMessage('error', 'Network error occurred. Please try again.');
        }
    };
});
</script>
</body>
</html>