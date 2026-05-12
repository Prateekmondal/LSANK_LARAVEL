@extends('layouts.app')

@section('title', 'Well Logging Services – Ankleshwar | Borehole Data Collection & Analysis')

@push('pagejs')
    <script src="{{ asset('/static/js/chart.js') }}"></script>
@endpush
@push('css')
    <link rel="preload" as="image" href="{{ asset('/static/images/logging-data-bg.png') }}">
    <link rel="stylesheet" type="text/css" href='{{ asset("/static/css/welcome.css") }}'>
@endpush

@push('css')
<style>
/* ════════════════════════════════════════════════════
   DESIGN TOKENS
   ════════════════════════════════════════════════════ */
:root {
    --bg:        #0a0d12;
    --bg-2:      #111620;
    --bg-3:      #161c28;
    --surface:   #1b2232;
    --border:    rgba(255,255,255,0.08);
    --amber:     #F5A623;
    --amber-d:   #c47e0f;
    --teal:      #00C9A7;
    --coral:     #FF6B6B;
    --purple:    #9B6DFF;
    --text-1:    #F0EDE4;
    --text-2:    #8A9BB0;
    --text-3:    #4A5568;
    --font-disp: 'Bebas Neue', sans-serif;
    --font-body: 'DM Sans', sans-serif;
    --font-mono: 'DM Mono', monospace;
    --radius:    12px;
    --radius-sm: 6px;
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth;font-size:16px}
body{font-family:var(--font-body);background:var(--bg);color:var(--text-1);overflow-x:hidden;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
ul{list-style:none}
img{max-width:100%;display:block}
button{cursor:pointer;border:none;background:none;font-family:inherit}

/* ════════════════════════════════════════════════════
   NAVBAR
   ════════════════════════════════════════════════════ */
.wls-nav{
    position:fixed;top:0;left:0;right:0;z-index:1000;
    transition:background .3s,backdrop-filter .3s,box-shadow .3s;
    padding:0 2rem;
}
.wls-nav.scrolled{
    background:rgba(10,13,18,.85);
    backdrop-filter:blur(16px);
    box-shadow:0 1px 0 var(--border);
}
.nav-inner{
    max-width:1320px;margin:0 auto;
    display:flex;align-items:center;gap:2.5rem;
    height:72px;
}
.nav-logo{display:flex;align-items:center;gap:.75rem}
.logo-abbr{
    font-family:var(--font-disp);font-size:2rem;color:var(--amber);
    line-height:1;letter-spacing:.05em;
}
.logo-text{font-size:.7rem;text-transform:uppercase;letter-spacing:.1em;color:var(--text-2);line-height:1.4}
.logo-text small{color:var(--amber);opacity:.8}

.nav-links{display:flex;align-items:center;gap:2rem;margin-left:auto}
.nav-links a{font-size:.85rem;font-weight:500;color:var(--text-2);letter-spacing:.05em;text-transform:uppercase;
    transition:color .2s;position:relative}
.nav-links a:hover{color:var(--text-1)}
.chevron{font-size:.7rem;opacity:.6}
.has-dropdown{position:relative}
.dropdown{
    position:absolute;top:calc(100% + 12px);left:0;min-width:180px;
    background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);
    opacity:0;pointer-events:none;transform:translateY(-8px);transition:.2s;padding:.5rem 0;
}
.has-dropdown:hover .dropdown{opacity:1;pointer-events:all;transform:translateY(0)}
.dropdown li a{display:block;padding:.6rem 1rem;font-size:.82rem;color:var(--text-2);transition:color .2s,background .2s}
.dropdown li a:hover{color:var(--text-1);background:rgba(255,255,255,.04)}

.nav-actions{display:flex;gap:.75rem;align-items:center}
.btn-ghost{font-size:.82rem;font-weight:500;color:var(--text-2);padding:.45rem 1rem;border-radius:var(--radius-sm);border:1px solid var(--border);transition:.2s}
.btn-ghost:hover{color:var(--text-1);border-color:rgba(255,255,255,.2)}
.btn-primary{font-size:.82rem;font-weight:600;background:var(--amber);color:#0a0d12;padding:.45rem 1.1rem;border-radius:var(--radius-sm);transition:.2s}
.btn-primary:hover{background:var(--amber-d)}

.hamburger{display:none;flex-direction:column;gap:5px;padding:.5rem}
.hamburger span{display:block;width:22px;height:2px;background:var(--text-1);border-radius:2px;transition:.3s}

/* ════════════════════════════════════════════════════
   HERO
   ════════════════════════════════════════════════════ */
.hero{
    position:relative;height:100vh;overflow:hidden;
    display:flex;align-items:center;
    background:var(--bg);
    padding-top:72px;
}

/* 3D perspective grid floor */
.hero-grid{
    position:absolute;bottom:0;left:0;right:0;height:55%;
    background-image:
        linear-gradient(rgba(245,166,35,.12) 1px,transparent 1px),
        linear-gradient(90deg,rgba(245,166,35,.12) 1px,transparent 1px);
    background-size:60px 60px;
    transform:perspective(600px) rotateX(65deg) translateY(20%);
    transform-origin:bottom center;
    mask-image:linear-gradient(to top,rgba(0,0,0,.5),transparent 80%);
    pointer-events:none;
}

/* Strata bands */
.strata-bands{
    position:absolute;left:0;right:0;bottom:0;height:100%;
    pointer-events:none;overflow:hidden;
}
.strata-band{
    position:absolute;left:0;right:0;
    border-top:1px solid rgba(245,166,35,.15);
}
.strata-band::before{
    content:attr(data-label);
    position:absolute;left:2rem;top:50%;transform:translateY(-50%);
    font-family:var(--font-disp);font-size:.9rem;letter-spacing:.15em;
    color:var(--amber);opacity:.25;writing-mode:vertical-rl;
    top:1.5rem;left:2%;
}
.s1{bottom:65%;height:20%;background:linear-gradient(to bottom,rgba(245,166,35,.03),transparent)}
.s2{bottom:40%;height:25%;background:linear-gradient(to bottom,rgba(0,201,167,.04),transparent)}
.s3{bottom:10%;height:30%;background:linear-gradient(to bottom,rgba(155,109,255,.04),transparent)}

/* Animated drill */
.drill-visual{
    position:absolute;right:10%;top:50%;transform:translateY(-50%);
    width:40px;display:flex;flex-direction:column;align-items:center;
    animation:drillDown 4s ease-in-out infinite;
}
@keyframes drillDown{0%,100%{transform:translateY(-52%)}50%{transform:translateY(-48%)}}
.drill-shaft{display:flex;flex-direction:column;gap:0}
.drill-segment{
    width:20px;height:40px;margin:0 auto;
    background:linear-gradient(90deg,#3a4155,#5a6275,#3a4155);
    border-top:1px solid rgba(255,255,255,.1);
    border-bottom:1px solid rgba(0,0,0,.3);
    animation:segPulse 2s ease-in-out infinite;
    animation-delay:calc(var(--i) * .15s);
}
@keyframes segPulse{0%,100%{filter:brightness(1)}50%{filter:brightness(1.2)}}
.drill-bit{
    width:0;height:0;
    border-left:10px solid transparent;
    border-right:10px solid transparent;
    border-top:24px solid #c47e0f;
}

/* Log trace panel */
.log-trace{
    position:absolute;right:4%;top:72px;bottom:0;width:80px;
    opacity:.6;display:flex;
}
.log-trace svg{flex:1}
.trace-gr{fill:none;stroke:var(--amber);stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.trace-res{fill:none;stroke:var(--teal);stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round}
.log-depth-ticks{
    position:absolute;right:0;top:0;bottom:0;
    display:flex;flex-direction:column;justify-content:space-between;padding:1rem 0;
}
.depth-tick{
    font-family:var(--font-mono);font-size:.65rem;color:var(--text-3);
    white-space:nowrap;
}

/* Hero content */
.hero-content{
    position:relative;z-index:2;
    padding:0 2rem 0 calc(max(5vw, 2rem));
    max-width:800px;
}
.hero-eyebrow{margin-bottom:1.5rem}
.eyebrow-badge{
    display:inline-flex;align-items:center;gap:.5rem;
    font-family:var(--font-mono);font-size:.75rem;letter-spacing:.12em;
    color:var(--amber);border:1px solid rgba(245,166,35,.3);
    padding:.35rem .9rem;border-radius:999px;
    text-transform:uppercase;
}
.hero-title{
    display:flex;flex-direction:column;
    font-family:var(--font-disp);line-height:.92;
    margin-bottom:1.5rem;
}
.title-line{
    font-size:clamp(5rem,12vw,11rem);
    letter-spacing:-.01em;
    -webkit-text-stroke:1px rgba(240,237,228,.15);
    color:transparent;
    animation:titleReveal .8s cubic-bezier(.22,1,.36,1) both;
}
.title-line.t1{color:var(--text-1);-webkit-text-stroke:0;animation-delay:.1s}
.title-line.t2{color:var(--amber);-webkit-text-stroke:0;animation-delay:.2s}
.title-line.t3{animation-delay:.3s}
@keyframes titleReveal{from{opacity:0;transform:translateY(40px)}to{opacity:1;transform:translateY(0)}}

.hero-sub{
    font-size:1.05rem;color:var(--text-2);line-height:1.7;
    margin-bottom:2.5rem;animation:titleReveal .8s .4s both;
}
.hero-ctas{display:flex;gap:1rem;flex-wrap:wrap;animation:titleReveal .8s .5s both}
.cta-primary{
    display:inline-flex;align-items:center;gap:.6rem;
    background:var(--amber);color:#0a0d12;
    font-weight:700;font-size:.9rem;letter-spacing:.05em;
    padding:.8rem 1.8rem;border-radius:var(--radius-sm);
    transition:transform .2s,box-shadow .2s;
}
.cta-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,166,35,.3)}
.cta-secondary{
    display:inline-flex;align-items:center;
    border:1px solid var(--border);color:var(--text-2);
    font-size:.9rem;letter-spacing:.05em;font-weight:500;
    padding:.8rem 1.8rem;border-radius:var(--radius-sm);
    transition:.2s;
}
.cta-secondary:hover{border-color:rgba(255,255,255,.25);color:var(--text-1)}

/* Depth ticker */
.depth-ticker{
    display:inline-flex;flex-direction:column;gap:.3rem;
    margin-top:3rem;padding:1rem 1.5rem;
    border:1px solid var(--border);border-radius:var(--radius-sm);
    background:rgba(255,255,255,.02);animation:titleReveal .8s .7s both;
}
.ticker-label{font-family:var(--font-mono);font-size:.65rem;letter-spacing:.15em;color:var(--text-3);text-transform:uppercase}
.ticker-value{font-family:var(--font-disp);font-size:2.4rem;color:var(--amber);letter-spacing:.05em;line-height:1}
.ticker-value small{font-size:1rem;color:var(--text-2);margin-left:.25rem}

/* Scanline */
.scanline{
    position:absolute;inset:0;pointer-events:none;
    background:repeating-linear-gradient(0deg,transparent,transparent 3px,rgba(0,0,0,.04) 3px,rgba(0,0,0,.04) 4px);
    animation:scanMove 8s linear infinite;
    opacity:.3;
}
@keyframes scanMove{from{background-position:0 0}to{background-position:0 100px}}


/* ════════════════════════════════════════════════════
   STATS BAND
   ════════════════════════════════════════════════════ */
.stats-band{
    background:var(--bg-2);
    border-top:1px solid var(--border);border-bottom:1px solid var(--border);
    padding:3rem 2rem;
}
.stats-inner{
    max-width:1100px;margin:0 auto;
    display:grid;grid-template-columns:repeat(4,1fr);gap:1px;
    background:var(--border);
    border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;
}
.stat-card{
    background:var(--bg-2);
    padding:2.5rem 1.5rem;
    display:flex;flex-direction:column;align-items:center;text-align:center;
    transition:background .3s;
    position:relative;overflow:hidden;
}
.stat-card::before{
    content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
    background:linear-gradient(90deg,var(--amber),transparent);
    transform:scaleX(0);transform-origin:left;transition:transform .4s;
}
.stat-card:hover{background:var(--bg-3)}
.stat-card:hover::before{transform:scaleX(1)}
.stat-icon{
    width:48px;height:48px;border-radius:50%;
    background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.2);
    display:flex;align-items:center;justify-content:center;
    color:var(--amber);margin-bottom:1rem;
}
.stat-value{font-family:var(--font-disp);font-size:3rem;color:var(--amber);letter-spacing:.05em;line-height:1}
.stat-label{font-size:.8rem;color:var(--text-2);text-transform:uppercase;letter-spacing:.1em;margin-top:.4rem}


/* ════════════════════════════════════════════════════
   SHARED SECTION
   ════════════════════════════════════════════════════ */
.section{padding:7rem 2rem}
.section-inner{max-width:1200px;margin:0 auto}
.section-tag{
    display:inline-block;font-family:var(--font-mono);font-size:.72rem;
    letter-spacing:.15em;color:var(--amber);text-transform:uppercase;
    border-left:2px solid var(--amber);padding-left:.75rem;
    margin-bottom:1rem;
}
.section-title{
    font-family:var(--font-disp);font-size:clamp(2.4rem,5vw,4rem);
    line-height:1.05;letter-spacing:.02em;color:var(--text-1);
}
.section-title em{font-style:normal;color:var(--amber)}
.section-header{margin-bottom:4rem}
.section-header.center{text-align:center}


/* ════════════════════════════════════════════════════
   CHARTS
   ════════════════════════════════════════════════════ */
.charts-section{background:var(--bg-2)}
.charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:2rem}
.chart-card{
    background:var(--bg-3);border:1px solid var(--border);
    border-radius:var(--radius);padding:2.5rem;
}
.chart-title{font-family:var(--font-disp);font-size:1.6rem;letter-spacing:.04em;color:var(--amber);margin-bottom:.4rem}
.chart-sub{font-size:.82rem;color:var(--text-2);margin-bottom:2rem}

/* Donut */
.donut-wrap{display:flex;align-items:center;gap:2rem;flex-wrap:wrap}
.donut-svg{width:180px;flex-shrink:0}
.donut-bg{fill:none;stroke:rgba(255,255,255,.05);stroke-width:28}
.donut-ring{fill:none;stroke-width:28;stroke-linecap:butt;transform:rotate(-90deg);transform-origin:center;
    animation:ringDraw 1.2s ease-out both}
@keyframes ringDraw{from{stroke-dasharray:0 502.65}to{}}
.r-openhole{stroke:#F5A623;animation-delay:.2s}
.r-casedhole{stroke:#00C9A7;animation-delay:.4s}
.r-production{stroke:#FF6B6B;animation-delay:.6s}
.donut-center-num{font-family:var(--font-disp);font-size:2rem;fill:var(--text-1);text-anchor:middle;dominant-baseline:middle;dy:-.5rem}
.donut-center-label{font-family:var(--font-mono);font-size:.55rem;fill:var(--text-3);text-anchor:middle;letter-spacing:.12em;dy:1.2rem}
.donut-legend{display:flex;flex-direction:column;gap:1rem}
.legend-item{display:flex;align-items:center;gap:.6rem;font-size:.82rem}
.legend-dot{width:10px;height:10px;border-radius:2px;flex-shrink:0}
.ld-openhole{background:var(--amber)}
.ld-casedhole{background:var(--teal)}
.ld-production{background:var(--coral)}
.legend-text{color:var(--text-2);flex:1}
.legend-item strong{color:var(--text-1);font-family:var(--font-mono)}

/* Bar Chart */
.bar-chart-wrap{display:flex;gap:.75rem;height:220px;align-items:flex-end}
.bar-y-axis{display:flex;flex-direction:column;justify-content:space-between;padding-bottom:1.5rem}
.bar-y-axis span{font-size:.65rem;color:var(--text-3);font-family:var(--font-mono)}
.bars-group{flex:1;display:flex;gap:1.5rem;align-items:flex-end;padding-bottom:1.5rem;position:relative}
.bars-group::before{content:'';position:absolute;inset:0 0 1.5rem 0;
    background:repeating-linear-gradient(to top,var(--border) 0,var(--border) 1px,transparent 1px,transparent 20%);
    pointer-events:none}
.bar-col{display:flex;flex-direction:column;align-items:center;gap:.6rem;flex:1;height:100%}
.bar-fill-wrap{flex:1;width:100%;display:flex;align-items:flex-end}
.bar-fill{
    width:100%;background:linear-gradient(to top,var(--amber),var(--amber-d));
    border-radius:4px 4px 0 0;
    height:var(--bar-pct);position:relative;
    animation:barRise 1s cubic-bezier(.22,1,.36,1) both;
}
@keyframes barRise{from{height:0}to{height:var(--bar-pct)}}
.bar-col:last-child .bar-fill{background:linear-gradient(to top,var(--teal),#009e80)}
.bar-val{position:absolute;top:-1.5rem;left:50%;transform:translateX(-50%);
    font-family:var(--font-mono);font-size:.72rem;color:var(--text-2)}
.bar-label{font-size:.72rem;color:var(--text-2);text-align:center;line-height:1.3}


/* ════════════════════════════════════════════════════
   SERVICES
   ════════════════════════════════════════════════════ */
.services-section{background:var(--bg);position:relative;overflow:hidden}
.services-bg-text{
    position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);
    font-family:var(--font-disp);font-size:22vw;color:transparent;
    -webkit-text-stroke:1px rgba(255,255,255,.03);
    white-space:nowrap;pointer-events:none;user-select:none;z-index:0;
}
.services-grid{
    display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;
    position:relative;z-index:1;
}
.srv-card{
    border:1px solid var(--border);border-radius:var(--radius);
    padding:2.5rem;position:relative;overflow:hidden;cursor:default;
    transition:border-color .3s,transform .3s;
    animation:cardIn .6s cubic-bezier(.22,1,.36,1) both;
    animation-delay:var(--card-delay);
}
@keyframes cardIn{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.srv-card:hover{transform:translateY(-4px)}
.srv-card.srv-amber:hover{border-color:rgba(245,166,35,.4)}
.srv-card.srv-coral:hover{border-color:rgba(255,107,107,.4)}
.srv-card.srv-teal:hover{border-color:rgba(0,201,167,.4)}
.srv-card.srv-purple:hover{border-color:rgba(155,109,255,.4)}

.srv-hover-bg{
    position:absolute;inset:0;
    opacity:0;transition:opacity .3s;
    pointer-events:none;
}
.srv-amber .srv-hover-bg{background:radial-gradient(circle at 30% 30%,rgba(245,166,35,.06),transparent 60%)}
.srv-coral .srv-hover-bg{background:radial-gradient(circle at 30% 30%,rgba(255,107,107,.06),transparent 60%)}
.srv-teal .srv-hover-bg{background:radial-gradient(circle at 30% 30%,rgba(0,201,167,.06),transparent 60%)}
.srv-purple .srv-hover-bg{background:radial-gradient(circle at 30% 30%,rgba(155,109,255,.06),transparent 60%)}
.srv-card:hover .srv-hover-bg{opacity:1}

.srv-card-inner{position:relative;z-index:1}
.srv-number{font-family:var(--font-disp);font-size:5rem;opacity:.05;position:absolute;top:-1.5rem;right:1.5rem;line-height:1;color:var(--text-1)}
.srv-icon{
    width:48px;height:48px;display:flex;align-items:center;justify-content:center;
    border-radius:var(--radius-sm);margin-bottom:1.5rem;
}
.srv-amber .srv-icon{background:rgba(245,166,35,.1);color:var(--amber)}
.srv-coral .srv-icon{background:rgba(255,107,107,.1);color:var(--coral)}
.srv-teal .srv-icon{background:rgba(0,201,167,.1);color:var(--teal)}
.srv-purple .srv-icon{background:rgba(155,109,255,.1);color:var(--purple)}

.srv-title{font-family:var(--font-disp);font-size:1.7rem;letter-spacing:.04em;margin-bottom:.75rem;color:var(--text-1)}
.srv-desc{font-size:.88rem;color:var(--text-2);line-height:1.7;margin-bottom:1.5rem}
.srv-link{
    display:inline-flex;align-items:center;gap:.4rem;
    font-size:.8rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;
    transition:gap .2s;
}
.srv-amber .srv-link{color:var(--amber)}
.srv-coral .srv-link{color:var(--coral)}
.srv-teal .srv-link{color:var(--teal)}
.srv-purple .srv-link{color:var(--purple)}
.srv-link:hover{gap:.7rem}


/* ════════════════════════════════════════════════════
   GALLERY
   ════════════════════════════════════════════════════ */
.gallery-section{background:var(--bg-2)}
.gallery-header{display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:3rem;gap:2rem;flex-wrap:wrap}
.btn-outline{
    display:inline-flex;align-items:center;gap:.5rem;
    border:1px solid var(--border);color:var(--text-2);
    font-size:.82rem;font-weight:500;letter-spacing:.05em;
    padding:.65rem 1.4rem;border-radius:var(--radius-sm);
    transition:.2s;white-space:nowrap;
}
.btn-outline:hover{border-color:rgba(255,255,255,.25);color:var(--text-1)}

.gallery-mosaic{
    display:grid;
    grid-template-columns:2fr 1fr;
    grid-template-rows:300px 200px;
    gap:1rem;
}
.mosaic-tile{
    position:relative;overflow:hidden;border-radius:var(--radius);
    background-size:cover;background-position:center;background-color:var(--bg-3);
    transition:transform .4s;
}
.mosaic-tile:hover{transform:scale(1.01)}
.tile-large{grid-row:1/3}
.tile-sm{grid-row:auto}
.tile-equipment{grid-column:2;grid-row:2}

.tile-overlay{
    position:absolute;bottom:0;left:0;right:0;
    padding:1.5rem;
    background:linear-gradient(to top,rgba(10,13,18,.9),transparent);
}
.tile-tag{
    display:inline-block;font-family:var(--font-mono);font-size:.65rem;
    letter-spacing:.12em;color:var(--amber);text-transform:uppercase;
    border:1px solid rgba(245,166,35,.3);padding:.2rem .6rem;border-radius:3px;
    margin-bottom:.5rem;
}
.tile-title{font-family:var(--font-disp);font-size:1.3rem;letter-spacing:.04em;color:var(--text-1)}

.tile-depth-meter{
    position:absolute;right:1.5rem;top:1.5rem;
    display:flex;flex-direction:column;align-items:center;gap:.4rem;
}
.dm-line{width:1px;height:60px;background:linear-gradient(to bottom,transparent,var(--amber))}
.tile-depth-meter span{font-family:var(--font-mono);font-size:.65rem;color:var(--amber)}

.equipment-specs{
    position:absolute;top:1rem;left:1rem;
    display:flex;flex-direction:column;gap:.4rem;
}
.spec-row{
    display:flex;gap:.5rem;align-items:center;
    font-family:var(--font-mono);font-size:.65rem;
    background:rgba(10,13,18,.7);border:1px solid var(--border);
    padding:.25rem .5rem;border-radius:3px;
}
.spec-row span{color:var(--text-3)}
.spec-row strong{color:var(--amber)}


/* ════════════════════════════════════════════════════
   LOG SHOWCASE
   ════════════════════════════════════════════════════ */
.log-showcase-section{background:var(--bg);overflow:hidden}
.log-showcase-inner{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.ls-para{font-size:.95rem;color:var(--text-2);line-height:1.8;margin:1.5rem 0 2rem}
.ls-features{display:flex;flex-direction:column;gap:.9rem}
.ls-features li{
    display:flex;align-items:center;gap:.7rem;
    font-size:.88rem;color:var(--text-2);
}
.ls-features li svg{color:var(--teal);flex-shrink:0}

/* 3D Log paper */
.log-display-3d{
    position:relative;
    transform:perspective(1000px) rotateY(-10deg) rotateX(5deg);
    transform-style:preserve-3d;
    transition:transform .5s;
}
.log-display-3d:hover{transform:perspective(1000px) rotateY(0deg) rotateX(0deg)}
.log-paper{
    background:#f5f0e0;border-radius:4px;
    overflow:hidden;position:relative;
    box-shadow:0 30px 80px rgba(0,0,0,.5);
}
.log-header-strip{
    background:#1a2035;padding:.5rem 1rem;
    display:flex;justify-content:space-around;
    font-family:var(--font-mono);font-size:.65rem;color:var(--amber);
    letter-spacing:.1em;
}
.log-tracks{display:flex;height:400px;border-bottom:1px solid #ddd}
.log-track{flex:1;border-right:1px solid #ddd;position:relative;overflow:hidden}
.log-track svg{width:100%;height:100%}
.track-fill-gr{fill:rgba(245,166,35,.2);stroke:none}
.track-line-gr{fill:none;stroke:#c47e0f;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.track-line-res{fill:none;stroke:#007a8a;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.track-line-sp{fill:none;stroke:#6b4c9a;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.depth-scale{
    padding:.3rem .5rem;
    display:flex;justify-content:space-between;
    font-family:var(--font-mono);font-size:.6rem;color:#666;
    background:#ece8d5;border-top:1px solid #ddd;
}
.ds-mark{writing-mode:vertical-rl;text-orientation:mixed}
.log-shadow{
    position:absolute;bottom:-20px;left:10%;right:5%;height:20px;
    background:rgba(0,0,0,.25);filter:blur(15px);
    transform:translateZ(-1px);
}
.log-paper-edge{
    position:absolute;top:0;right:-8px;bottom:0;width:8px;
    background:linear-gradient(90deg,#c8c0a8,#e8e0c8);
    transform:rotateY(90deg) translateX(-4px);
}


/* ════════════════════════════════════════════════════
   CONTACT
   ════════════════════════════════════════════════════ */
.contact-section{background:var(--bg-2);position:relative;overflow:hidden}
.contact-geo-bg{position:absolute;inset:0;pointer-events:none}
.geo-circle{position:absolute;border-radius:50%;border:1px solid rgba(245,166,35,.06)}
.c1{width:500px;height:500px;right:-100px;top:-150px}
.c2{width:300px;height:300px;right:50px;top:-50px}
.geo-line{position:absolute;background:rgba(245,166,35,.04)}
.l1{width:100%;height:1px;top:40%}
.l2{width:1px;height:100%;right:38%}

.contact-inner{
    display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start;
    position:relative;z-index:1;
}
.contact-details{display:flex;flex-direction:column;gap:1.5rem;margin-top:2.5rem}
.cd-item{display:flex;align-items:flex-start;gap:1rem}
.cd-icon{
    width:40px;height:40px;border-radius:var(--radius-sm);
    background:rgba(245,166,35,.08);border:1px solid rgba(245,166,35,.2);
    display:flex;align-items:center;justify-content:center;color:var(--amber);flex-shrink:0;
}
.cd-label{font-size:.72rem;color:var(--text-3);text-transform:uppercase;letter-spacing:.1em;margin-bottom:.25rem;font-family:var(--font-mono)}
.cd-value{font-size:.9rem;color:var(--text-2);line-height:1.5}
.cd-value a{color:var(--text-1);transition:color .2s}
.cd-value a:hover{color:var(--amber)}

.contact-form-wrap{
    background:var(--bg-3);border:1px solid var(--border);
    border-radius:var(--radius);padding:2.5rem;
}
.contact-form{display:flex;flex-direction:column;gap:1.25rem}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.form-group{display:flex;flex-direction:column;gap:.4rem}
.form-group label{font-size:.75rem;color:var(--text-2);text-transform:uppercase;letter-spacing:.1em;font-family:var(--font-mono)}
.form-group input,.form-group textarea{
    background:var(--bg-2);border:1px solid var(--border);
    border-radius:var(--radius-sm);padding:.7rem 1rem;
    color:var(--text-1);font-family:var(--font-body);font-size:.9rem;
    transition:border-color .2s;resize:none;
}
.form-group input::placeholder,.form-group textarea::placeholder{color:var(--text-3)}
.form-group input:focus,.form-group textarea:focus{
    outline:none;border-color:rgba(245,166,35,.5);
    box-shadow:0 0 0 3px rgba(245,166,35,.06);
}
.form-error{font-size:.75rem;color:var(--coral)}
.submit-btn{
    display:inline-flex;align-items:center;justify-content:center;gap:.6rem;
    background:var(--amber);color:#0a0d12;
    font-weight:700;font-size:.9rem;letter-spacing:.05em;
    padding:.9rem 2rem;border-radius:var(--radius-sm);
    transition:transform .2s,box-shadow .2s;margin-top:.5rem;
}
.submit-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(245,166,35,.3)}
.form-success{
    background:rgba(0,201,167,.08);border:1px solid rgba(0,201,167,.3);
    color:var(--teal);border-radius:var(--radius-sm);padding:.75rem 1rem;font-size:.85rem;
}


/* ════════════════════════════════════════════════════
   FOOTER
   ════════════════════════════════════════════════════ */
.wls-footer{background:var(--bg);border-top:1px solid var(--border);padding:5rem 2rem 2rem}
.footer-top{
    max-width:1200px;margin:0 auto;
    display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:3rem;
    margin-bottom:4rem;
}
.footer-logo{display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem}
.footer-tagline{font-size:.85rem;color:var(--text-2);line-height:1.7;max-width:280px;margin-bottom:1.5rem}
.footer-socials{display:flex;gap:.75rem}
.social-link{
    width:36px;height:36px;border-radius:50%;
    border:1px solid var(--border);color:var(--text-2);
    display:flex;align-items:center;justify-content:center;
    transition:.2s;
}
.social-link:hover{border-color:var(--amber);color:var(--amber)}

.footer-links-col h4,.footer-contact-col h4{
    font-family:var(--font-disp);font-size:1rem;letter-spacing:.08em;
    color:var(--text-1);margin-bottom:1.25rem;
}
.footer-links-col ul,.footer-contact-col ul{display:flex;flex-direction:column;gap:.65rem}
.footer-links-col li a{font-size:.83rem;color:var(--text-2);transition:color .2s}
.footer-links-col li a:hover{color:var(--amber)}
.footer-contact-col li{display:flex;align-items:flex-start;gap:.6rem;font-size:.82rem;color:var(--text-2)}
.footer-contact-col li svg{flex-shrink:0;color:var(--amber);margin-top:2px}
.footer-contact-col li a{color:var(--text-2);transition:color .2s}
.footer-contact-col li a:hover{color:var(--amber)}

.footer-bottom{
    max-width:1200px;margin:0 auto;
    padding-top:2rem;border-top:1px solid var(--border);
    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;
}
.footer-dev{font-size:.78rem;color:var(--text-3)}
.footer-dev strong{color:var(--text-2)}
.footer-copy{font-size:.78rem;color:var(--text-3)}


/* ════════════════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════════════════ */
@media(max-width:1024px){
    .stats-inner{grid-template-columns:repeat(2,1fr)}
    .charts-grid,.contact-inner,.log-showcase-inner{grid-template-columns:1fr}
    .footer-top{grid-template-columns:1fr 1fr}
    .services-grid{grid-template-columns:1fr}
    .gallery-mosaic{grid-template-columns:1fr;grid-template-rows:auto}
    .tile-large,.tile-equipment{grid-column:auto;grid-row:auto}
}
@media(max-width:768px){
    .nav-links,.nav-actions{display:none}
    .hamburger{display:flex}
    .title-line{font-size:clamp(3.5rem,16vw,7rem)}
    .hero-content{padding:2rem}
    .stats-inner{grid-template-columns:1fr 1fr}
    .form-row{grid-template-columns:1fr}
    .footer-top{grid-template-columns:1fr}
    .footer-bottom{flex-direction:column;text-align:center}
    .log-display-3d{transform:none}
}
</style>
@endpush

@section('content')

{{-- ============================================================
     NAVBAR
     ============================================================ --}}
<nav class="wls-nav" id="wls-nav">
    <div class="nav-inner">
        <a href="{{ route('home') }}" class="nav-logo">
            <span class="logo-abbr">WLS</span>
            <span class="logo-text">Logging Services<br><small>Ankleshwar</small></span>
        </a>
        <ul class="nav-links">
            <li><a href="{{ route('jcr.index') }}">JCR</a></li>
            <li class="has-dropdown">
                <a href="#">Checklists <span class="chevron">▾</span></a>
                <ul class="dropdown">
                    <li><a href="#">Pre-Job</a></li>
                    <li><a href="#">Post-Job</a></li>
                </ul>
            </li>
            <li class="has-dropdown">
                <a href="#">Time Register <span class="chevron">▾</span></a>
                <ul class="dropdown">
                    <li><a href="#">Daily Log</a></li>
                    <li><a href="#">Monthly Summary</a></li>
                </ul>
            </li>
        </ul>
        <div class="nav-actions">
            @guest
                <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Register</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-primary">Dashboard</a>
            @endguest
        </div>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

{{-- ============================================================
     HERO
     ============================================================ --}}
<section class="hero" id="hero">
    {{-- 3D grid floor --}}
    <div class="hero-grid"></div>

    {{-- Geological strata layers --}}
    <div class="strata-bands">
        <div class="strata-band s1" data-label="Uninvaded Zone"></div>
        <div class="strata-band s2" data-label="Transition Zone"></div>
        <div class="strata-band s3" data-label="Flushed Zone"></div>
    </div>

    {{-- Animated drill string --}}
    <div class="drill-visual">
        <div class="drill-shaft">
            <div class="drill-segment" style="--i:0"></div>
            <div class="drill-segment" style="--i:1"></div>
            <div class="drill-segment" style="--i:2"></div>
            <div class="drill-segment" style="--i:3"></div>
            <div class="drill-segment" style="--i:4"></div>
            <div class="drill-segment" style="--i:5"></div>
            <div class="drill-segment" style="--i:6"></div>
        </div>
        <div class="drill-bit"></div>
    </div>

    {{-- Log trace animation --}}
    <div class="log-trace">
        <svg viewBox="0 0 60 700" preserveAspectRatio="none">
            <polyline points="30,0 45,60 15,130 50,200 20,280 55,340 10,420 48,490 25,560 40,630 20,700" 
                      class="trace-gr"/>
            <polyline points="30,0 55,80 20,150 45,220 10,300 50,370 15,440 52,510 22,580 42,650 18,700"
                      class="trace-res"/>
        </svg>
        <div class="log-depth-ticks">
            @for($i = 0; $i < 7; $i++)
            <div class="depth-tick" style="--di:{{ $i }}">{{ 1200 + $i * 50 }}m</div>
            @endfor
        </div>
    </div>

    <div class="hero-content">
        <div class="hero-eyebrow">
            <span class="eyebrow-badge">Est. 1962 · Ankleshwar, Gujarat</span>
        </div>
        <h1 class="hero-title">
            <span class="title-line t1">WELL</span>
            <span class="title-line t2">LOGGING</span>
            <span class="title-line t3">SERVICES</span>
        </h1>
        <p class="hero-sub">
            Precise borehole data collection &amp; analysis for<br>
            oil, gas &amp; water sectors across Gujarat and beyond.
        </p>
        <div class="hero-ctas">
            <a href="#" class="cta-primary">
                <span>Contact Us</span>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#" class="cta-secondary">Our Services</a>
        </div>

        {{-- Live depth ticker --}}
        <div class="depth-ticker">
            <span class="ticker-label">CURRENT DEPTH</span>
            <span class="ticker-value" id="depthTicker">1,847<small>m</small></span>
        </div>
    </div>

    {{-- Scanline overlay for that instrument feel --}}
    <div class="scanline"></div>
</section>


{{-- ============================================================
     STATS BAND
     ============================================================ --}}
<section class="stats-band">
    <div class="stats-inner">
        @php
        $stats = [
            ['value' => '500+', 'label' => 'Projects Completed', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
            ['value' => '98%', 'label' => 'Accuracy Rate', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['value' => '20+', 'label' => 'Years Experience', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['value' => '50+', 'label' => 'Clients Served', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="stat-card" data-aos="fade-up">
            <div class="stat-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <div class="stat-value">{{ $stat['value'] }}</div>
            <div class="stat-label">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>
</section>


{{-- ============================================================
     DISTRIBUTION CHARTS
     ============================================================ --}}
<section class="section charts-section" id="analytics">
    <div class="section-inner">
        <div class="section-header">
            <div class="section-tag">FY 2024–25</div>
            <h2 class="section-title">Job Distribution<br><em>Analytics</em></h2>
        </div>

        <div class="charts-grid">
            {{-- Donut Chart --}}
            <div class="chart-card" data-chart="donut">
                <h3 class="chart-title">Logging Job Type Distribution</h3>
                <p class="chart-sub">Breakdown of completed jobs for FY 2024-25</p>
                <div class="donut-wrap">
                    <svg viewBox="0 0 220 220" class="donut-svg">
                        <circle class="donut-bg" cx="110" cy="110" r="80"/>
                        {{-- Open Hole: 380 jobs –– 380/610 ≈ 62.3% of 502.65 arc = 313.15 --}}
                        <circle class="donut-ring r-openhole" cx="110" cy="110" r="80"
                                stroke-dasharray="312 190" stroke-dashoffset="126"/>
                        {{-- Cased Hole: 180 jobs –– 180/610 ≈ 29.5% = 148.28 --}}
                        <circle class="donut-ring r-casedhole" cx="110" cy="110" r="80"
                                stroke-dasharray="148 354" stroke-dashoffset="-186"/>
                        {{-- Production: 50 jobs –– 50/610 ≈ 8.2% = 41.20 --}}
                        <circle class="donut-ring r-production" cx="110" cy="110" r="80"
                                stroke-dasharray="41 461" stroke-dashoffset="-334"/>
                        <text x="110" y="103" class="donut-center-num">610</text>
                        <text x="110" y="120" class="donut-center-label">JOBS</text>
                    </svg>
                    <div class="donut-legend">
                        <div class="legend-item">
                            <span class="legend-dot ld-openhole"></span>
                            <span class="legend-text">Open Hole Logging</span>
                            <strong>380</strong>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot ld-casedhole"></span>
                            <span class="legend-text">Cased Hole Logging</span>
                            <strong>180</strong>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot ld-production"></span>
                            <span class="legend-text">Production Logging</span>
                            <strong>50</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bar Chart --}}
            <div class="chart-card" data-chart="bar">
                <h3 class="chart-title">Job Support Distribution</h3>
                <p class="chart-sub">By agency for FY 2024-25</p>
                <div class="bar-chart-wrap">
                    @php
                    $barData = [
                        ['label' => 'Departmental', 'val' => 420, 'max' => 500],
                        ['label' => 'Contractual', 'val' => 190, 'max' => 500],
                    ];
                    @endphp
                    <div class="bar-y-axis">
                        @for($i = 5; $i >= 0; $i--)
                        <span>{{ $i * 100 }}</span>
                        @endfor
                    </div>
                    <div class="bars-group">
                        @foreach($barData as $bar)
                        <div class="bar-col">
                            <div class="bar-fill-wrap">
                                <div class="bar-fill" style="--bar-pct: {{ ($bar['val'] / $bar['max']) * 100 }}%">
                                    <span class="bar-val">{{ $bar['val'] }}</span>
                                </div>
                            </div>
                            <span class="bar-label">{{ $bar['label'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     SERVICES
     ============================================================ --}}
<section class="section services-section" id="services">
    <div class="services-bg-text" aria-hidden="true">SERVICES</div>
    <div class="section-inner">
        <div class="section-header center">
            <div class="section-tag">What We Offer</div>
            <h2 class="section-title">Comprehensive<br><em>Well Logging Solutions</em></h2>
        </div>

        <div class="services-grid">
            @php
            $services = [
                [
                    'title' => 'Borehole Logging',
                    'desc' => 'Comprehensive multi-parameter logging — gamma ray, resistivity, SP, caliper, and density-neutron porosity done during borehole operations.',
                    'icon' => 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12',
                    'accent' => 'amber',
                    'link' => '#',
                ],
                [
                    'title' => 'Oil &amp; Gas Logging',
                    'desc' => 'Advanced logging services for oil and gas — monitoring formation pressures and product equipment.',
                    'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
                    'accent' => 'coral',
                    'link' => '#',
                ],
                [
                    'title' => 'Data Interpretation',
                    'desc' => 'Expert analysis and interpretation of logging data with detailed reports — turning raw formation data into actionable insights.',
                    'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'accent' => 'teal',
                    'link' => '#',
                ],
                [
                    'title' => 'Equipment Rental',
                    'desc' => 'High-quality logging equipment available for rent with full technical support and field maintenance from our experienced team.',
                    'icon' => 'M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z',
                    'accent' => 'purple',
                    'link' => '#',
                ],
            ];
            @endphp

            @foreach($services as $i => $srv)
            <div class="srv-card srv-{{ $srv['accent'] }}" style="--card-delay: {{ $i * 0.1 }}s">
                <div class="srv-card-inner">
                    <div class="srv-number">0{{ $i + 1 }}</div>
                    <div class="srv-icon">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $srv['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="srv-title">{!! $srv['title'] !!}</h3>
                    <p class="srv-desc">{{ $srv['desc'] }}</p>
                    <a href="{{ $srv['link'] }}" class="srv-link">
                        Learn more
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="srv-hover-bg"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ============================================================
     GALLERY / PROJECT SHOWCASE
     ============================================================ --}}
<section class="section gallery-section" id="gallery">
    <div class="section-inner">
        <div class="gallery-header">
            <div>
                <div class="section-tag">Field Operations</div>
                <h2 class="section-title">Project<br><em>Gallery</em></h2>
            </div>
            <a href="#" class="btn-outline">View All Projects</a>
        </div>

        <div class="gallery-mosaic">
            {{-- Primary large tile --}}
            <div class="mosaic-tile tile-large" style="background-image: url('{{ asset('images/gallery/oil-rig.jpg') }}')">
                <div class="tile-overlay">
                    <span class="tile-tag">Field Operations</span>
                    <h4 class="tile-title">Oil Well Drilling Site</h4>
                </div>
                <div class="tile-depth-meter">
                    <div class="dm-line"></div>
                    <span>↓ 2,340m</span>
                </div>
            </div>

            {{-- Secondary tiles --}}
            <div class="mosaic-tile tile-sm" style="background-image: url('{{ asset('images/gallery/data-analysis.jpg') }}')">
                <div class="tile-overlay">
                    <span class="tile-tag">Data Science</span>
                    <h4 class="tile-title">Data Analysis Team</h4>
                </div>
            </div>

            <div class="mosaic-tile tile-sm" style="background-image: url('{{ asset('images/gallery/field-team.jpg') }}')">
                <div class="tile-overlay">
                    <span class="tile-tag">Personnel</span>
                    <h4 class="tile-title">Field Team in Action</h4>
                </div>
            </div>

            {{-- Logging equipment card --}}
            <div class="mosaic-tile tile-equipment" style="background-image: url('{{ asset('images/gallery/logging-equipment.jpg') }}')">
                <div class="tile-overlay">
                    <span class="tile-tag">Equipment</span>
                    <h4 class="tile-title">Logging Tools</h4>
                </div>
                <div class="equipment-specs">
                    <div class="spec-row"><span>GR Sonde</span><strong>API</strong></div>
                    <div class="spec-row"><span>Resistivity</span><strong>Ω·m</strong></div>
                    <div class="spec-row"><span>SP</span><strong>mV</strong></div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     LOG TRACE SHOWCASE  (Unique 3D Section)
     ============================================================ --}}
<section class="section log-showcase-section" id="log-showcase">
    <div class="section-inner log-showcase-inner">
        <div class="log-showcase-text">
            <div class="section-tag">Formation Evaluation</div>
            <h2 class="section-title">Reading the<br><em>Earth's Layers</em></h2>
            <p class="ls-para">
                Our wireline and LWD logging tools decode subsurface geology in real-time —
                GR, resistivity, SP, caliper and porosity curves rendered at the wellsite
                and delivered as interpreted reports.
            </p>
            <ul class="ls-features">
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Real-time digital acquisition
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Multi-parameter simultaneous logging
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Calibrated to regional standards
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                    Interpretive petrophysical reports
                </li>
            </ul>
        </div>

        {{-- Simulated log display --}}
        <div class="log-display-3d">
            <div class="log-paper">
                <div class="log-header-strip">
                    <span>GR</span><span>ILD</span><span>SP</span><span>CAL</span>
                </div>
                <div class="log-tracks">
                    <div class="log-track track-gr">
                        <svg viewBox="0 0 60 600" preserveAspectRatio="none">
                            <path class="track-fill-gr" d="M30,0 Q50,40 20,80 Q55,130 15,180 Q50,230 25,280 Q55,330 10,390 Q52,440 20,490 Q48,540 18,590 L60,590 L60,0 Z"/>
                            <polyline class="track-line-gr" points="30,0 50,40 20,80 55,130 15,180 50,230 25,280 55,330 10,390 52,440 20,490 48,540 18,590"/>
                        </svg>
                    </div>
                    <div class="log-track track-res">
                        <svg viewBox="0 0 60 600" preserveAspectRatio="none">
                            <polyline class="track-line-res" points="10,0 55,50 8,110 52,170 12,240 58,300 5,370 50,430 15,500 53,560 10,600"/>
                        </svg>
                    </div>
                    <div class="log-track track-sp">
                        <svg viewBox="0 0 60 600" preserveAspectRatio="none">
                            <polyline class="track-line-sp" points="30,0 25,60 38,120 22,180 40,250 20,310 42,380 18,450 38,510 24,570 36,600"/>
                        </svg>
                    </div>
                </div>
                <div class="depth-scale">
                    @for($d = 0; $d < 6; $d++)
                    <div class="ds-mark">{{ 1800 + $d * 50 }}m</div>
                    @endfor
                </div>
            </div>
            <div class="log-shadow"></div>
            <div class="log-paper-edge"></div>
        </div>
    </div>
</section>


{{-- ============================================================
     CONTACT
     ============================================================ --}}
<section class="section contact-section" id="contact">
    <div class="contact-geo-bg" aria-hidden="true">
        <div class="geo-circle c1"></div>
        <div class="geo-circle c2"></div>
        <div class="geo-line l1"></div>
        <div class="geo-line l2"></div>
    </div>
    <div class="section-inner contact-inner">
        {{-- Info Column --}}
        <div class="contact-info">
            <div class="section-tag">Get In Touch</div>
            <h2 class="section-title">For Your Well<br><em>Logging Requirements</em></h2>

            <div class="contact-details">
                <div class="cd-item">
                    <div class="cd-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="cd-label">Office Address</div>
                        <div class="cd-value">7N2C, Ankleshwar<br>Gujarat 393001, India</div>
                    </div>
                </div>
                <div class="cd-item">
                    <div class="cd-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <div class="cd-label">Phone</div>
                        <div class="cd-value"><a href="tel:+911234567890">+91 1234567890</a></div>
                    </div>
                </div>
                <div class="cd-item">
                    <div class="cd-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="cd-label">Email</div>
                        <div class="cd-value"><a href="mailto:info@loggingservicesankleshwar.com">info@loggingservicesankleshwar.com</a></div>
                    </div>
                </div>
                <div class="cd-item">
                    <div class="cd-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="cd-label">Working Hours</div>
                        <div class="cd-value">Monday – Friday: 09:30 AM – 05:30 PM</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="contact-form-wrap">
            <form action="#" method="POST" class="contact-form" novalidate>
                @csrf
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" placeholder="e.g. Rajesh Patel" required value="{{ old('name') }}">
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="you@company.com" required value="{{ old('email') }}">
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+91 98765 43210" value="{{ old('phone') }}">
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Tell us about your well logging requirements…" required>{{ old('message') }}</textarea>
                    @error('message') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="submit-btn">
                    <span>Send Message</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                </button>
                @if(session('success'))
                <div class="form-success">{{ session('success') }}</div>
                @endif
            </form>
        </div>
    </div>
</section>


{{-- ============================================================
     FOOTER
     ============================================================ --}}
<footer class="wls-footer">
    <div class="footer-top">
        <div class="footer-brand">
            <div class="footer-logo">
                <span class="logo-abbr">WLS</span>
                <span class="logo-text">Logging Services<br><small>Ankleshwar</small></span>
            </div>
            <p class="footer-tagline">
                Logging Services, Ankleshwar has been maintaining high standards of work ethics since 1962. Trusted in oil, gas, and water sectors.
            </p>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook" class="social-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                <a href="#" aria-label="LinkedIn" class="social-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <a href="mailto:info@loggingservicesankleshwar.com" aria-label="Email" class="social-link">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </a>
            </div>
        </div>

        <div class="footer-links-col">
            <h4>Useful Links</h4>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('jcr.index') }}">Add New JCR</a></li>
                <li><a href="{{ route('checklists.index') }}">Checklists</a></li>
                <li><a href="#">Time Register</a></li>
                <li><a href="#">Gallery</a></li>
            </ul>
        </div>

        <div class="footer-links-col">
            <h4>Services</h4>
            <ul>
                <li><a href="#">Borehole Logging</a></li>
                <li><a href="#">Oil &amp; Gas Logging</a></li>
                <li><a href="#">Data Interpretation</a></li>
                <li><a href="#">Equipment Rental</a></li>
            </ul>
        </div>

        <div class="footer-contact-col">
            <h4>Contact</h4>
            <ul>
                <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Ankleshwar, Gujarat 393001</span>
                </li>
                <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.27 9.26 19.79 19.79 0 011.22 4.6a2 2 0 011.99-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 9.91a16 16 0 006.06 6.06l1.48-1.55a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <a href="tel:+911234567890">+91 123 450 7890</a>
                </li>
                <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 014.27 9.26 19.79 19.79 0 011.22 4.6a2 2 0 011.99-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.91 9.91a16 16 0 006.06 6.06l1.48-1.55a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    <a href="tel:+911245671900">+91 124 567 1900</a>
                </li>
                <li>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <a href="mailto:info@loggingservicesankleshwar.com">info@loggingservicesankleshwar.com</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-dev">
            <strong>Developed &amp; Designed by:</strong>
            <span>Pratesh Mandal · DTT · CFM685</span>
        </div>
        <div class="footer-copy">
            &copy; {{ date('Y') }} Logging Services, Ankleshwar. All rights reserved.
        </div>
    </div>
</footer>

@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Job Statistics Pie Chart
            const jobStatsData = {
                labels: [
                    'Open Hole Logging',
                    'Cased Hole Logging',
                    'Production Logging'
                ],
                datasets: [{
                    data: [180, 780, 50],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#f39c12'
                    ],
                    borderWidth: 0
                }]
            };

            const jobStatsCtxpie = document.getElementById('jobStatsChartpie').getContext('2d');
            const jobStatsChartpie = new Chart(jobStatsCtxpie, {
                type: 'pie',
                data: jobStatsData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Job Statistics Pie Chart
            const jobStatsDatabar = {
                labels: [
                    'Departmental',
                    'Contractual'
                ],
                datasets: [{
                    data: [750, 250],
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71'
                    ],
                    borderWidth: 0
                }]
            };

            const jobStatsCtxbar = document.getElementById('jobStatsChartbar').getContext('2d');
            const jobStatsChartbar = new Chart(jobStatsCtxbar, {
                type: 'bar',
                data: jobStatsDatabar,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} jobs (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });

            // Create custom legend
            const chartLegend = document.getElementById('chartLegend');
            jobStatsData.labels.forEach((label, i) => {
                const legendItem = document.createElement('div');
                legendItem.className = 'legend-item';

                const legendColor = document.createElement('div');
                legendColor.className = 'legend-color';
                legendColor.style.backgroundColor = jobStatsData.datasets[0].backgroundColor[i];

                const legendText = document.createElement('span');
                legendText.textContent = `${label}: ${jobStatsData.datasets[0].data[i]} jobs`;

                legendItem.appendChild(legendColor);
                legendItem.appendChild(legendText);
                chartLegend.appendChild(legendItem);
            });

            // Content reveal animation
            const contentReveals = document.querySelectorAll('.content-reveal');

            const revealOnScroll = () => {
                contentReveals.forEach(element => {
                    const elementTop = element.getBoundingClientRect().top;
                    const elementBottom = element.getBoundingClientRect().bottom;

                    if (elementTop < window.innerHeight - 100 && elementBottom > 0) {
                        element.classList.add('active');
                    }
                });
            };

            // Initial check
            revealOnScroll();

            // Check on scroll
            window.addEventListener('scroll', revealOnScroll);

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
@endpush

@push('scripts')
<script>
// Navbar scroll state
const nav = document.getElementById('wls-nav');
window.addEventListener('scroll', () => {
    nav.classList.toggle('scrolled', window.scrollY > 20);
});

// Depth ticker animation
const ticker = document.getElementById('depthTicker');
if (ticker) {
    let depth = 1847;
    setInterval(() => {
        depth += Math.floor(Math.random() * 3);
        const small = ticker.querySelector('small');
        ticker.textContent = depth.toLocaleString();
        ticker.appendChild(small);
    }, 1500);
}

// Bar chart animation on scroll
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.querySelectorAll('.bar-fill').forEach(b => b.style.animationPlayState = 'running');
        }
    });
}, { threshold: .3 });
document.querySelectorAll('.chart-card[data-chart="bar"]').forEach(el => observer.observe(el));
</script>
@endpush