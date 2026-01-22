// Simple solution - just remove blue and white arrows
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to remove blue and white arrow elements
    function removeBlueWhiteArrows() {
        console.log('Removing blue and white arrows...'); // Debug log
        
        // Remove all SVG elements (blue/white arrows)
        const svgElements = document.querySelectorAll('.doc_accordion svg, .card-header svg, button[data-bs-toggle="collapse"] svg, button[aria-expanded] svg');
        console.log('Found SVG elements:', svgElements.length); // Debug log
        svgElements.forEach(svg => {
            svg.style.display = 'none';
            svg.style.visibility = 'hidden';
            svg.style.opacity = '0';
            svg.style.position = 'absolute';
            svg.style.left = '-99999px';
            svg.remove();
        });
        
        // Remove all icon elements (blue/white arrows)
        const iconElements = document.querySelectorAll('.doc_accordion i, .card-header i, button[data-bs-toggle="collapse"] i, button[aria-expanded] i');
        console.log('Found icon elements:', iconElements.length); // Debug log
        iconElements.forEach(icon => {
            if (icon.className.includes('fa-') || icon.className.includes('icon-') || icon.className.includes('chevron') || icon.className.includes('arrow')) {
                icon.style.display = 'none';
                icon.style.visibility = 'hidden';
                icon.style.opacity = '0';
                icon.remove();
            }
        });
        
        // Remove any Font Awesome or icon font elements
        const faElements = document.querySelectorAll('.doc_accordion [class*="fa-"], .card-header [class*="fa-"], .doc_accordion [class*="icon-"], .card-header [class*="icon-"]');
        console.log('Found FA/icon elements:', faElements.length); // Debug log
        faElements.forEach(element => {
            element.style.display = 'none';
            element.remove();
        });
        
        // Remove WordPress emoji images that might be used as arrows
        const emojiImages = document.querySelectorAll('img[src*="s.w.org/images/core/emoji"], img[src*="25b6.svg"], img[src*="25c0.svg"], img[src*="25b2.svg"], img[src*="25bc.svg"]');
        console.log('Found emoji images:', emojiImages.length); // Debug log
        emojiImages.forEach(img => {
            img.style.display = 'none';
            img.style.visibility = 'hidden';
            img.style.opacity = '0';
            img.remove();
        });
        
        // Remove emoji images in accordion contexts
        const accordionEmojis = document.querySelectorAll('.doc_accordion img[src*="emoji"], .card-header img[src*="emoji"], button[data-bs-toggle="collapse"] img[src*="emoji"], button[aria-expanded] img[src*="emoji"]');
        console.log('Found accordion emoji images:', accordionEmojis.length); // Debug log
        accordionEmojis.forEach(img => {
            img.style.display = 'none';
            img.remove();
        });
    }
    
    // Run immediately
    removeBlueWhiteArrows();
    
    // Run multiple times with different delays to catch dynamic content
    setTimeout(removeBlueWhiteArrows, 100);
    setTimeout(removeBlueWhiteArrows, 200);
    setTimeout(removeBlueWhiteArrows, 500);
    setTimeout(removeBlueWhiteArrows, 1000);
    setTimeout(removeBlueWhiteArrows, 2000);
    
    // Continuous monitoring for first 5 seconds
    let monitorCount = 0;
    const continuousMonitor = setInterval(function() {
        removeBlueWhiteArrows();
        monitorCount++;
        if (monitorCount >= 10) { // Stop after 5 seconds (10 * 500ms)
            clearInterval(continuousMonitor);
        }
    }, 500);
    
    // Watch for DOM changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                removeBlueWhiteArrows();
                setTimeout(removeBlueWhiteArrows, 50);
                setTimeout(removeBlueWhiteArrows, 100);
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Remove arrows when accordion buttons are clicked
    document.addEventListener('click', function(e) {
        if (e.target.matches('.doc_accordion .card-header button, [data-bs-toggle="collapse"]') || 
            e.target.closest('.doc_accordion .card-header button') || 
            e.target.closest('[data-bs-toggle="collapse"]')) {
            
            console.log('Accordion clicked, removing arrows...');
            removeBlueWhiteArrows();
            setTimeout(removeBlueWhiteArrows, 50);
            setTimeout(removeBlueWhiteArrows, 100);
            setTimeout(removeBlueWhiteArrows, 200);
            setTimeout(removeBlueWhiteArrows, 500);
        }
    });
    
    // Listen for Bootstrap collapse events
    document.addEventListener('shown.bs.collapse', function(e) {
        console.log('Collapse shown');
        removeBlueWhiteArrows();
        setTimeout(removeBlueWhiteArrows, 50);
    });
    
    document.addEventListener('hidden.bs.collapse', function(e) {
        console.log('Collapse hidden');
        removeBlueWhiteArrows();
        setTimeout(removeBlueWhiteArrows, 50);
    });
});
