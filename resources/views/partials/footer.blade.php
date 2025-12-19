<footer class="w-full text-white flex justify-center mt-[5rem] py-4">
    <div class="max-w-2/3 mx-auto flex flex-col md:flex-row items-center justify-between gap-4">

        <div class="text-center md:text-left text-sm font-light flex-1 whitespace-nowrap">
            &copy; 2025 <span class="font-semibold">HaLoy, Inc.</span>
        </div>

        <div class="flex justify-center gap-6 text-sm font-light flex-1">
            <a href="{{ route('accueil') }}" class="hover:underline">Home</a>
            <a href="{{ route('contact') }}" class="hover:underline">Contact</a>
            <a href="{{ route('articles.all') }}" class="hover:underline">Articles</a>
            <span>@haloy</span>
        </div>
        
        <div class="flex items-center justify-center gap-4 flex-1">
            <a href="#" aria-label="Instagram" class="text-xl"><i class="fab fa-instagram"></i></a>
            <a href="#" aria-label="X" class="text-xl"><i class="fab fa-x-twitter"></i></a>
            <a href="#" aria-label="YouTube" class="text-xl"><i class="fab fa-youtube"></i></a>
        </div>
    </div>
</footer>