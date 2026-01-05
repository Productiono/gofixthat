<?php
/* Template Name: App AiBoot */

get_header();
?>

<style>
.app-aibot-page {
    font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
    color: #1f1f22;
    background: #f7f6f3;
}

.app-aibot-page .section-wrap {
    background: #f7f6f3;
}

.app-aibot-page .inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 72px 24px;
}

.app-aibot-page .hero {
    padding-top: 48px;
    background: linear-gradient(180deg, #f7f6f3 0%, #f3f1ee 100%);
}

.app-aibot-page .hero-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 32px;
    align-items: center;
}

.app-aibot-page .badge-row {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.app-aibot-page .badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    padding: 6px 12px;
    border-radius: 999px;
    background: #e4e1dc;
    color: #44414c;
    text-transform: uppercase;
    letter-spacing: 0.02em;
    font-weight: 600;
}

.app-aibot-page .badge svg,
.app-aibot-page .badge img {
    width: 16px;
    height: 16px;
}

.app-aibot-page h1 {
    font-size: 32px;
    line-height: 1.28;
    margin: 10px 0 16px;
    color: #221f23;
    letter-spacing: -0.01em;
}

.app-aibot-page .hero p.lead {
    font-size: 16px;
    line-height: 1.7;
    color: #4c4b52;
    margin-bottom: 18px;
}

.app-aibot-page .hero .small-label {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: #8f8c96;
}

.app-aibot-page .hero .cta-row {
    display: flex;
    align-items: center;
    gap: 14px;
}

.app-aibot-page .btn-primary {
    background: #18191d;
    color: #fdfbf7;
    padding: 14px 22px;
    border-radius: 10px;
    border: none;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
}

.app-aibot-page .btn-link {
    color: #343037;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.app-aibot-page .hero-illustration {
    background: #ffffff;
    border-radius: 18px;
    padding: 24px;
    border: 1px solid #e3e0da;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
}

.app-aibot-page .hero-illustration img {
    width: 100%;
    border-radius: 12px;
    display: block;
}

.app-aibot-page .quote {
    text-align: center;
    padding: 52px 24px 60px;
    background: #f2f0ec;
    font-size: 18px;
    color: #1e1c22;
    line-height: 1.7;
    font-weight: 600;
    border-top: 1px solid #e6e1db;
    border-bottom: 1px solid #e6e1db;
}

.app-aibot-page .quote span {
    display: block;
    margin-top: 12px;
    font-size: 14px;
    font-weight: 600;
    color: #7b7781;
}

.app-aibot-page .dark-section {
    background: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.06), transparent 35%),
                radial-gradient(circle at 80% 10%, rgba(255,255,255,0.04), transparent 35%),
                #0f1115;
    color: #f7f6f3;
}

.app-aibot-page .dark-section .inner {
    padding: 80px 24px 90px;
}

.app-aibot-page .section-title {
    text-align: center;
    font-size: 22px;
    letter-spacing: 0.02em;
    color: #f5f3ef;
    margin-bottom: 12px;
}

.app-aibot-page .section-subtitle {
    text-align: center;
    color: #b5b2bc;
    font-size: 15px;
    max-width: 720px;
    margin: 0 auto 42px;
    line-height: 1.7;
}

.app-aibot-page .feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 18px;
}

.app-aibot-page .feature-card {
    border-radius: 18px;
    padding: 22px;
    background: linear-gradient(145deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
    border: 1px solid rgba(255,255,255,0.06);
    box-shadow: 0 14px 36px rgba(0,0,0,0.35);
}

.app-aibot-page .feature-card img.icon {
    width: 48px;
    height: 48px;
    margin-bottom: 14px;
}

.app-aibot-page .feature-card h3 {
    font-size: 16px;
    margin: 0 0 8px;
    color: #f9f7f3;
}

.app-aibot-page .feature-card p {
    margin: 0 0 10px;
    color: #b7b4be;
    line-height: 1.6;
    font-size: 14px;
}

.app-aibot-page .tag-list {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.app-aibot-page .tag {
    padding: 8px 12px;
    border-radius: 10px;
    background: rgba(255,255,255,0.06);
    color: #d8d6de;
    font-size: 13px;
}

.app-aibot-page .live-demos {
    background: #f7f6f3;
}

.app-aibot-page .demos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 22px;
    align-items: start;
}

.app-aibot-page .demo-card {
    background: #fff;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 16px 44px rgba(0,0,0,0.08);
    border: 1px solid #e3e0da;
}

.app-aibot-page .demo-card .chrome-bar {
    display: flex;
    gap: 6px;
    margin-bottom: 12px;
}

.app-aibot-page .demo-card .dot {
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: #d05c56;
}

.app-aibot-page .demo-card .dot.yellow { background: #e2b04a; }
.app-aibot-page .demo-card .dot.green { background: #5aa565; }

.app-aibot-page .demo-card img {
    width: 100%;
    border-radius: 14px;
}

.app-aibot-page .demo-text {
    background: #fff;
    border-radius: 18px;
    padding: 22px;
    border: 1px solid #e5e0da;
    box-shadow: 0 12px 40px rgba(0,0,0,0.07);
}

.app-aibot-page .demo-text h4 {
    font-size: 15px;
    color: #a0a4ae;
    margin: 0 0 10px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.app-aibot-page .demo-text h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #1f1e23;
}

.app-aibot-page .demo-text p {
    margin: 0;
    color: #5b5960;
    line-height: 1.6;
    font-size: 14px;
}

.app-aibot-page .demos-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 22px;
}

.app-aibot-page .demo-card.gradient {
    background: linear-gradient(135deg, #f9b3c5, #f1c0d7, #b6f3be);
    padding: 20px;
}

.app-aibot-page .demo-card.gradient .demo-inner {
    background: #fff;
    border-radius: 16px;
    padding: 16px;
}

.app-aibot-page .latest-posts {
    background: #f7f6f3;
    text-align: center;
}

.app-aibot-page .post-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 18px;
    margin-top: 22px;
}

.app-aibot-page .post-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e3e0da;
    box-shadow: 0 12px 32px rgba(0,0,0,0.06);
    text-align: left;
}

.app-aibot-page .post-card img {
    width: 100%;
    display: block;
}

.app-aibot-page .post-card .body {
    padding: 14px 16px 16px;
}

.app-aibot-page .post-card .tag-line {
    font-size: 13px;
    color: #a5a2ab;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    margin-bottom: 6px;
}

.app-aibot-page .post-card h5 {
    margin: 0 0 6px;
    font-size: 16px;
    color: #27252b;
}

.app-aibot-page .post-card .date {
    font-size: 13px;
    color: #8e8b95;
}

.app-aibot-page .faq {
    background: #f7f6f3;
}

.app-aibot-page .faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.app-aibot-page .faq-item {
    background: #fff;
    padding: 18px 20px;
    border-radius: 14px;
    border: 1px solid #e3e0da;
    box-shadow: 0 12px 30px rgba(0,0,0,0.05);
}

.app-aibot-page .faq-item summary {
    font-weight: 700;
    font-size: 15px;
    color: #25232a;
    cursor: pointer;
}

.app-aibot-page .faq-item p {
    color: #5d5a64;
    line-height: 1.6;
    margin: 10px 0 0;
    font-size: 14px;
}

.app-aibot-page .cta-dark {
    background: #0f1115;
    color: #f7f6f3;
    text-align: center;
    padding: 72px 24px 64px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.app-aibot-page .cta-dark h2 {
    font-size: 24px;
    margin-bottom: 10px;
    letter-spacing: -0.01em;
}

.app-aibot-page .cta-dark p {
    color: #b7b5be;
    max-width: 620px;
    margin: 0 auto 20px;
    line-height: 1.7;
}

@media (max-width: 768px) {
    .app-aibot-page .inner {
        padding: 60px 18px;
    }

    .app-aibot-page h1 {
        font-size: 28px;
    }

    .app-aibot-page .hero-illustration {
        padding: 16px;
    }
}
</style>

<main class="app-aibot-page">
    <section class="hero section-wrap">
        <div class="inner hero-grid">
            <div>
                <div class="badge-row">
                    <span class="badge">Welcome</span>
                </div>
                <h1>Desktop agents that use computers like a human — at cloud scale.</h1>
                <p class="lead">Desktop bots that inhabit both the web, sandboxed container and computers. They are multiple agents in the swarm, these clicking and typing things for you.</p>
                <p class="small-label">Roles</p>
                <p class="lead">Code editor, Data Entry</p>
                <div class="cta-row">
                    <a class="btn-primary" href="#">Get Started</a>
                    <a class="btn-link" href="#">Demo Video →</a>
                </div>
            </div>
            <div class="hero-illustration">
                <img src="https://via.placeholder.com/800x520" alt="App AiBoot hero placeholder" />
            </div>
        </div>
    </section>

    <section class="quote section-wrap">
        <div class="inner">
            <div>“Desktop agents are the missing link between LLMs and real work.”</div>
            <span>— The Age of UX Desktop Agents, blog</span>
        </div>
    </section>

    <section class="dark-section">
        <div class="inner">
            <div class="section-title">Why a Desktop Agent</div>
            <div class="section-subtitle">A desktop agent is the ideal automation solution because it works just like a person, making it universally compatible with any software.</div>
            <div class="feature-grid">
                <div class="feature-card">
                    <img class="icon" src="https://via.placeholder.com/48" alt="Firefox icon" />
                    <h3>Firefox</h3>
                    <p>A complete computer</p>
                    <p>Desktop agents work like a human. They understand images, enter text, click, copy, and paste. They can use any software.</p>
                </div>
                <div class="feature-card">
                    <img class="icon" src="https://via.placeholder.com/48" alt="Control icon" />
                    <h3>Fine-grained control</h3>
                    <p>Desktop agents understand user interfaces and can control software, using the same keyboard and mouse operations as humans.</p>
                </div>
                <div class="feature-card">
                    <img class="icon" src="https://via.placeholder.com/48" alt="Tasks icon" />
                    <h3>Tasks in minutes</h3>
                    <p>Once an employee is logged in, a desktop agent that automates it can be set up in minutes.</p>
                </div>
                <div class="feature-card">
                    <img class="icon" src="https://via.placeholder.com/48" alt="History icon" />
                    <h3>History and replays</h3>
                    <p>Session replays make it easy to review interactions and understand how the agent works.</p>
                </div>
                <div class="feature-card">
                    <img class="icon" src="https://via.placeholder.com/48" alt="Cloud icon" />
                    <h3>Secure to private clouds</h3>
                    <p>Desktop agents live in fully isolated sandboxes, with access only to the applications needed for each workflow.</p>
                    <div class="tag-list">
                        <span class="tag">AWS</span>
                        <span class="tag">Google Cloud</span>
                        <span class="tag">Azure</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="live-demos section-wrap">
        <div class="inner">
            <div class="section-title" style="color:#1f1e23;">Live Demos</div>
            <div class="section-subtitle" style="color:#6a6871;">Watch how desktop agents automate complex tasks from booking to security.</div>
            <div class="demos-grid">
                <div class="demo-card gradient">
                    <div class="demo-inner">
                        <img src="https://via.placeholder.com/800x460" alt="Live demo placeholder" />
                    </div>
                </div>
                <div class="demo-text">
                    <h4>Live demo #1</h4>
                    <h3>Booking and checking into a flight</h3>
                    <p>A desktop agent books a flight, checks the traveler into the flight, and even finds the refund policy and submits it to the company’s system for future reference.</p>
                </div>
            </div>
            <div class="demos-row">
                <div class="demo-card gradient">
                    <div class="demo-inner">
                        <img src="https://via.placeholder.com/800x460" alt="2FA login placeholder" />
                    </div>
                </div>
                <div class="demo-text">
                    <h4>Live demo #2</h4>
                    <h3>Making secure logins with 2FA</h3>
                    <p>A desktop agent uses an authenticator app, sends the two-factor code to the user by email and securely logs into the target system.</p>
                </div>
            </div>
            <div class="demos-row" style="margin-top:26px;">
                <div class="demo-card gradient">
                    <div class="demo-inner">
                        <img src="https://via.placeholder.com/800x460" alt="Development workflows placeholder" />
                    </div>
                </div>
                <div class="demo-text">
                    <h4>Live demo #3</h4>
                    <h3>Extending Development Workflows</h3>
                    <p>A desktop agent can refactor code, search documentation, review code, and enforce style guidelines across repositories.</p>
                </div>
            </div>
            <div class="demos-row" style="margin-top:26px;">
                <div class="demo-card gradient">
                    <div class="demo-inner">
                        <img src="https://via.placeholder.com/800x460" alt="Technical research placeholder" />
                    </div>
                </div>
                <div class="demo-text">
                    <h4>Live demo #4</h4>
                    <h3>Technical Research &amp; Summarization</h3>
                    <p>By reading docs, synthesizing content, and writing a report, a desktop agent can summarize any workflow and provide a clean handoff to the entire company.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="latest-posts section-wrap">
        <div class="inner">
            <div class="section-title" style="color:#1f1e23;">Latest posts</div>
            <div class="section-subtitle" style="color:#6a6871;">News and updates from the team.</div>
            <div class="post-grid">
                <div class="post-card">
                    <img src="https://via.placeholder.com/360x200" alt="Latest post 1" />
                    <div class="body">
                        <div class="tag-line">News</div>
                        <h5>The AiBoot core — Free Linux GUI template for agent Outlook runtime.</h5>
                        <div class="date">Jul 27, 2023</div>
                    </div>
                </div>
                <div class="post-card">
                    <img src="https://via.placeholder.com/360x200" alt="Latest post 2" />
                    <div class="body">
                        <div class="tag-line">News</div>
                        <h5>Why the simplest Desktop Agent Automation wins</h5>
                        <div class="date">Jul 27, 2023</div>
                    </div>
                </div>
                <div class="post-card">
                    <img src="https://via.placeholder.com/360x200" alt="Latest post 3" />
                    <div class="body">
                        <div class="tag-line">News</div>
                        <h5>The Age of the Desktop Agents is here</h5>
                        <div class="date">May 12, 2023</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="faq section-wrap">
        <div class="inner">
            <div class="section-title" style="color:#1f1e23;">Frequently Asked Questions</div>
            <div class="section-subtitle" style="color:#6a6871;">Get answers to common questions about using desktop agents.</div>
            <div class="faq-grid">
                <details class="faq-item">
                    <summary>What is a desktop agent?</summary>
                    <p>Desktop agents can understand interfaces, execute clicks and keystrokes, and access multiple tools to complete tasks end-to-end.</p>
                </details>
                <details class="faq-item">
                    <summary>How are desktop agents different?</summary>
                    <p>They operate exactly like a human within your computer, making them compatible with any software.</p>
                </details>
                <details class="faq-item">
                    <summary>Can desktop agents handle authentication?</summary>
                    <p>Yes, they can interact with login prompts, authenticator apps, and secure environments.</p>
                </details>
                <details class="faq-item">
                    <summary>How much do desktop agents cost?</summary>
                    <p>Pricing can be customized to each workflow and usage profile.</p>
                </details>
            </div>
        </div>
    </section>

    <section class="cta-dark">
        <div class="section-title" style="color:#f7f6f3;">Ready to Hire Your First Desktop Agent?</div>
        <p>Start automating all the high-value workflows in minutes — no coding required.</p>
        <a class="btn-primary" href="#">Get started</a>
    </section>
</main>

<?php
get_footer();
