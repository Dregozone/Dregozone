<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
    x-data="{
        base64String: '',
        preview: '',
        fileName: '',
        fileSize: '',
        copied: false,
        convertFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.fileName = file.name;
            this.fileSize = (file.size / 1024).toFixed(1) + ' KB';
            const reader = new FileReader();
            reader.onload = (e) => {
                this.base64String = e.target.result;
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        copyToClipboard() {
            if (!this.base64String) return;
            navigator.clipboard.writeText(this.base64String).then(() => {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2000);
            });
        },
        selectAll(event) {
            event.target.select();
        }
    }">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Image Converter</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                Upload an image file to generate its base64 string for use in project and blog post images.
            </p>
        </div>
        <a href="{{ route('admin.blog.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
            ← Back to Admin
        </a>
    </div>

    <!-- Upload Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <label for="image-upload" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            Select an Image File
        </label>
        <input
            type="file"
            id="image-upload"
            accept="image/*"
            @change="convertFile($event)"
            class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Supports JPEG, PNG, GIF, WebP and other common image formats. The conversion happens entirely in your browser — nothing is uploaded to the server.
        </p>
    </div>

    <!-- Preview Section -->
    <template x-if="preview">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Image Preview</h2>
            <div class="flex items-start gap-6">
                <img :src="preview" alt="Preview" class="h-48 w-auto rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                    <p><span class="font-medium text-gray-900 dark:text-white">File:</span> <span x-text="fileName"></span></p>
                    <p><span class="font-medium text-gray-900 dark:text-white">Size:</span> <span x-text="fileSize"></span></p>
                    <p><span class="font-medium text-gray-900 dark:text-white">Base64 length:</span> <span x-text="base64String.length.toLocaleString() + ' characters'"></span></p>
                </div>
            </div>
        </div>
    </template>

    <!-- Base64 Output Section -->
    <template x-if="base64String">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Base64 String</h2>
                <button
                    @click="copyToClipboard()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
                    :class="copied
                        ? 'bg-green-600 text-white'
                        : 'bg-blue-600 hover:bg-blue-700 text-white'">
                    <template x-if="!copied">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </template>
                    <template x-if="copied">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    <span x-text="copied ? 'Copied!' : 'Copy to Clipboard'"></span>
                </button>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                Click inside the text area to select all, or use the Copy button above. Paste this entire string into the image field when creating or editing a project or blog post.
            </p>

            <textarea
                :value="base64String"
                @click="selectAll($event)"
                readonly
                rows="8"
                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-xs focus:outline-none focus:ring-blue-500 focus:border-blue-500 resize-none"
                placeholder="Base64 string will appear here after selecting an image..."></textarea>

            <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-lg">
                <p class="text-sm text-amber-800 dark:text-amber-200">
                    <strong>How to use:</strong> Copy the base64 string above, then go to the
                    <a href="{{ route('admin.projects.create') }}" class="underline hover:text-amber-900 dark:hover:text-amber-100">Create Project</a>
                    or
                    <a href="{{ route('admin.blog.create') }}" class="underline hover:text-amber-900 dark:hover:text-amber-100">Create Blog Post</a>
                    page and paste it into the image field.
                </p>
            </div>
        </div>
    </template>

    <!-- Placeholder when no file selected -->
    <template x-if="!base64String">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-dashed border-gray-300 dark:border-gray-600 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                Select an image file above to generate its base64 string.
            </p>
        </div>
    </template>
</div>
