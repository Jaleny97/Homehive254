<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | HomeHive</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">

    <div class="max-w-lg mx-auto bg-white p-8 rounded-3xl shadow-lg border border-gray-100">
        <h2 class="text-2xl font-black text-gray-900 mb-6">Add New Product</h2>

        <form action="/add-product" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="e.g. Modern Velvet Sofa" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="Describe the item's condition and features..." required></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Price (KSh)</label>
                <input type="number" name="price" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="e.g. 5000" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Upload Product Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Category</label>
                <select name="category" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-orange-500 outline-none bg-white" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach(['Furniture', 'Electronics', 'Kitchenware', 'Decor', 'Bedding'] as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white py-4 rounded-2xl font-bold hover:bg-orange-700 transition shadow-lg shadow-orange-200 mt-4">
                Save Product
            </button>
        </form>
    </div>

</body>
</html>