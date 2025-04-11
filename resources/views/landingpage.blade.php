<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexFleetDynamics - Bus Transportation Recruitment Specialists</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-base-100">
    <!-- Navigation -->
    <div class="navbar bg-primary text-primary-content">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52 text-neutral">
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#careers">Careers</a></li>
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl">NexFleetDynamics</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="#about">About Us</a></li>
                <li><a href="#careers">Careers</a></li>
                <li><a href="#benefits">Benefits</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a href="{{ route('welcome') }}" class="btn btn-accent">View Job Postings</a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero min-h-screen" style="background-image: url(https://images.unsplash.com/photo-1509822929063-6b6cfc9b42f2?q=80&w=2070&auto=format&fit=crop);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">Drive Your Career Forward</h1>
                <p class="mb-5">NexFleetDynamics connects skilled professionals with top bus transportation companies across the nation. Start your journey with us today.</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary">Explore Opportunities</a>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="py-20 px-4 max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row gap-8 items-center">
            <div class="lg:w-1/2">
                <h2 class="text-4xl font-bold mb-6">About NexFleetDynamics</h2>
                <p class="mb-4">We specialize in recruiting and placing top talent in the bus transportation industry, from professional drivers to maintenance technicians and operations staff.</p>
                <p class="mb-4">With over 15 years of experience, we've built strong relationships with transportation companies nationwide, helping them find qualified professionals who meet strict safety and compliance standards.</p>
                <div class="stats shadow">
                    <div class="stat">
                        <div class="stat-title">Drivers Placed</div>
                        <div class="stat-value text-primary">1,200+</div>
                        <div class="stat-desc">Last 12 months</div>
                    </div>
                    <div class="stat">
                        <div class="stat-title">Partner Companies</div>
                        <div class="stat-value text-secondary">85+</div>
                        <div class="stat-desc">Nationwide</div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2">
                <img src="https://images.unsplash.com/photo-1601758003122-53c40e686a19?q=80&w=2070&auto=format&fit=crop" alt="Bus driver" class="rounded-lg shadow-2xl">
            </div>
        </div>
    </div>

    <!-- Careers Section -->
    <div id="careers" class="py-20 bg-neutral text-neutral-content">
        <div class="px-4 max-w-6xl mx-auto">
            <h2 class="text-4xl font-bold mb-12 text-center">Career Opportunities</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Career Card 1 -->
                <div class="card bg-base-100 text-neutral shadow-xl">
                    <figure><img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?q=80&w=2069&auto=format&fit=crop" alt="Bus Driver" /></figure>
                    <div class="card-body">
                        <h3 class="card-title">Commercial Bus Drivers</h3>
                        <p>CDL holders needed for school, transit, and charter routes. Competitive pay and benefits.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Apply Now</a>
                        </div>
                    </div>
                </div>
                
                <!-- Career Card 2 -->
                <div class="card bg-base-100 text-neutral shadow-xl">
                    <figure><img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?q=80&w=1964&auto=format&fit=crop" alt="Mechanic" /></figure>
                    <div class="card-body">
                        <h3 class="card-title">Fleet Mechanics</h3>
                        <p>Skilled technicians for maintenance and repair of bus fleets. Diesel experience preferred.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Apply Now</a>
                        </div>
                    </div>
                </div>
                
                <!-- Career Card 3 -->
                <div class="card bg-base-100 text-neutral shadow-xl">
                    <figure><img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop" alt="Dispatcher" /></figure>
                    <div class="card-body">
                        <h3 class="card-title">Transportation Dispatchers</h3>
                        <p>Coordinate routes and schedules. Strong communication skills required.</p>
                        <div class="card-actions justify-end">
                            <a href="{{ route('welcome') }}" class="btn btn-primary">Apply Now</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('welcome') }}" class="btn btn-accent btn-lg">View All Job Postings</a>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div id="benefits" class="py-20 px-4 max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold mb-12 text-center">Why Choose NexFleetDynamics?</h2>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Benefit 1 -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <div class="text-5xl mb-4">💰</div>
                    <h3 class="card-title">Competitive Pay</h3>
                    <p>Industry-leading wages with regular performance reviews</p>
                </div>
            </div>
            
            <!-- Benefit 2 -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <div class="text-5xl mb-4">🏥</div>
                    <h3 class="card-title">Comprehensive Benefits</h3>
                    <p>Health, dental, vision, and retirement plans</p>
                </div>
            </div>
            
            <!-- Benefit 3 -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <div class="text-5xl mb-4">🎓</div>
                    <h3 class="card-title">Training Programs</h3>
                    <p>Paid CDL training and ongoing professional development</p>
                </div>
            </div>
            
            <!-- Benefit 4 -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body items-center text-center">
                    <div class="text-5xl mb-4">🚌</div>
                    <h3 class="card-title">Modern Fleet</h3>
                    <p>Well-maintained vehicles with the latest safety features</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="py-20 bg-base-200">
        <div class="px-4 max-w-6xl mx-auto">
            <h2 class="text-4xl font-bold mb-12 text-center">What Our Team Says</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Testimonial 1 -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="avatar">
                                <div class="w-16 rounded-full">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" />
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold">Michael Rodriguez</h4>
                                <p class="text-sm opacity-70">Transit Driver, 3 years</p>
                            </div>
                        </div>
                        <p>"NexFleetDynamics helped me transition from trucking to passenger transport. Their training program was comprehensive and their support team is always available when I have questions."</p>
                        <div class="rating rating-md rating-half mt-4">
                            <input type="radio" name="rating-1" class="rating-hidden" />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-2" checked />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-2" checked />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-1" class="bg-orange-400 mask mask-star-2 mask-half-2" />
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="avatar">
                                <div class="w-16 rounded-full">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" />
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold">Sarah Johnson</h4>
                                <p class="text-sm opacity-70">Fleet Mechanic, 2 years</p>
                            </div>
                        </div>
                        <p>"I appreciate the focus on safety and the quality of equipment we work with. The benefits package is better than any other shop I've worked at in this industry."</p>
                        <div class="rating rating-md rating-half mt-4">
                            <input type="radio" name="rating-2" class="rating-hidden" />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-2" checked />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-2" checked />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-1" checked />
                            <input type="radio" name="rating-2" class="bg-orange-400 mask mask-star-2 mask-half-2" checked />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="py-20 px-4 max-w-6xl mx-auto">
        <h2 class="text-4xl font-bold mb-12 text-center">Get In Touch</h2>
        
        <div class="flex flex-col lg:flex-row gap-12">
            <div class="lg:w-1/2">
                <h3 class="text-2xl font-bold mb-4">Contact Information</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Main Office</p>
                            <p>123 Transportation Way, Suite 400<br>Atlanta, GA 30328</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Recruitment Hotline</p>
                            <p>(800) 555-1234</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold">Email</p>
                            <p>careers@nexfleetdynamics.com</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-2xl font-bold mb-4">Business Hours</h3>
                    <div class="overflow-x-auto">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Monday - Friday</th>
                                    <td>8:00 AM - 6:00 PM</td>
                                </tr>
                                <tr>
                                    <th>Saturday</th>
                                    <td>9:00 AM - 2:00 PM</td>
                                </tr>
                                <tr>
                                    <th>Sunday</th>
                                    <td>Closed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-1/2">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title">Send Us a Message</h3>
                        <form class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Your Name</span>
                                </label>
                                <input type="text" placeholder="John Doe" class="input input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Your Email</span>
                                </label>
                                <input type="email" placeholder="john@example.com" class="input input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Phone Number</span>
                                </label>
                                <input type="tel" placeholder="(555) 123-4567" class="input input-bordered" required />
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Position Interested In</span>
                                </label>
                                <select class="select select-bordered">
                                    <option disabled selected>Select position</option>
                                    <option>Bus Driver</option>
                                    <option>Fleet Mechanic</option>
                                    <option>Dispatcher</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text">Message</span>
                                </label>
                                <textarea class="textarea textarea-bordered h-24" placeholder="Your message here..."></textarea>
                            </div>
                            <div class="form-control mt-6">
                                <button class="btn btn-primary">Send Message</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-10 bg-neutral text-neutral-content">
        <div class="max-w-6xl mx-auto px-4 w-full">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <span class="footer-title">Services</span> 
                    <a class="link link-hover">Driver Recruitment</a>
                    <a class="link link-hover">Mechanic Placement</a>
                    <a class="link link-hover">Operations Staffing</a>
                    <a class="link link-hover">Training Programs</a>
                </div> 
                <div>
                    <span class="footer-title">Company</span> 
                    <a href="#about" class="link link-hover">About us</a>
                    <a href="#contact" class="link link-hover">Contact</a>
                    <a class="link link-hover">Press kit</a>
                </div> 
                <div>
                    <span class="footer-title">Legal</span> 
                    <a class="link link-hover">Terms of use</a>
                    <a class="link link-hover">Privacy policy</a>
                    <a class="link link-hover">Cookie policy</a>
                </div> 
                <div>
                    <span class="footer-title">Newsletter</span> 
                    <div class="form-control w-80">
                        <label class="label">
                            <span class="label-text">Enter your email address</span>
                        </label> 
                        <div class="relative">
                            <input type="text" placeholder="username@site.com" class="input input-bordered w-full pr-16" /> 
                            <button class="btn btn-primary absolute top-0 right-0 rounded-l-none">Subscribe</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <footer class="footer px-10 py-4 border-t bg-neutral text-neutral-content border-base-300">
        <div class="max-w-6xl mx-auto px-4 w-full">
            <div class="items-center grid-flow-col">
                <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" class="fill-current">
                    <path d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.378-2.136-.237-2.553-1.149-.418-.91-.467-1.77-.234-2.648.197-.933 1.018-1.74 2.051-1.97l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path>
                </svg>
                <p>NexFleetDynamics © 2023 - All rights reserved</p>
            </div> 
            <div class="md:place-self-center md:justify-self-end">
                <div class="grid grid-flow-col gap-4">
                    <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path></svg></a> 
                    <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path></svg></a>
                    <a><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="fill-current"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path></svg></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>