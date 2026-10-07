/* ============================================================
   UIU Research Portal — Mock Data Store
   ============================================================ */

const DATA = {

  /*  Current user (mock session)  */
  currentUser: {
    id: 1,
    name: "Rafsan Ahmed",
    email: "rafsan.ahmed@uiu.ac.bd",
    avatar: null,
    initials: "RA",
    department: "Computer Science & Engineering",
    year: "3rd Year",
    role: "Graduate Student",
    bio: "Passionate about AI, machine learning, and healthcare applications. Looking for collaborative minds!",
    skills: ["Python", "Machine Learning", "TensorFlow", "React", "Data Analysis"],
    interests: ["AI in Healthcare", "Computer Vision", "NLP", "Edge Computing"],
    reputation: 2840,
    rank: 4,
    badge: "Active Researcher",
    completedProjects: 7,
    ongoingProjects: 2,
    contributions: 134,
    joined: "September 2022"
  },

  /*  Students  */
  students: [
    { id: 2, name: "Nusrat Jahan", initials: "NJ", color: "purple", department: "EEE", year: "4th Year", skills: ["VLSI", "IoT", "Arduino", "Signal Processing"], interests: ["Smart Grid", "Embedded Systems"], reputation: 3200, rank: 2, status: "online", lookingFor: ["IoT experts", "Data analysts"], project: "Smart Grid Monitoring System" },
    { id: 3, name: "Tanvir Hossain", initials: "TH", color: "green", department: "CSE", year: "2nd Year", skills: ["React", "Node.js", "MongoDB", "TypeScript"], interests: ["Web Dev", "System Design"], reputation: 1850, rank: 9, status: "offline", lookingFor: ["Backend devs", "UI designers"], project: "E-learning Platform" },
    { id: 4, name: "Sadia Islam", initials: "SI", color: "orange", department: "BBA", year: "3rd Year", skills: ["Data Analysis", "Excel", "Python", "Market Research"], interests: ["Fintech", "Business Analytics"], reputation: 2100, rank: 7, status: "online", lookingFor: ["CSE students", "Statisticians"], project: "Fintech Startup Analysis" },
    { id: 5, name: "Rakibul Islam", initials: "RI", color: "accent", department: "CSE", year: "4th Year", skills: ["AI", "PyTorch", "Computer Vision", "C++"], interests: ["Autonomous Systems", "Robotics"], reputation: 4100, rank: 1, status: "online", lookingFor: ["ML engineers", "Robotics enthusiasts"], project: "Autonomous Drone Navigation" },
    { id: 6, name: "Farhan Kabir", initials: "FK", color: "purple", department: "ME", year: "3rd Year", skills: ["CAD", "MATLAB", "Thermal Analysis", "FEM"], interests: ["Renewable Energy", "Heat Transfer"], reputation: 1650, rank: 12, status: "offline", lookingFor: ["CSE collaborators", "EEE students"], project: "Solar Panel Efficiency Optimizer" },
    { id: 7, name: "Maliha Rahman", initials: "MR", color: "green", department: "CSE", year: "2nd Year", skills: ["Python", "Data Science", "SQL", "Tableau"], interests: ["Healthcare Data", "Bioinformatics"], reputation: 2300, rank: 6, status: "online", lookingFor: ["Medical students", "ML experts"], project: "COVID Pattern Analysis" },
    { id: 8, name: "Imran Chowdhury", initials: "IC", color: "orange", department: "CSE", year: "4th Year", skills: ["Blockchain", "Solidity", "Web3", "React"], interests: ["DeFi", "Smart Contracts"], reputation: 2650, rank: 5, status: "away", lookingFor: ["Business analysts", "Legal experts"], project: "Decentralized Voting System" },
  ],

  /*  Projects  */
  projects: [
    {
      id: 1, title: "AI-Based Healthcare Diagnosis", status: "active",
      description: "Leveraging deep learning to assist in early disease detection from medical imaging data.",
      domain: "AI / Healthcare", members: [1, 7, 5], progress: 65,
      tasks: { todo: 5, inProgress: 3, done: 12 }, deadline: "2025-06-30",
      tags: ["Machine Learning", "Healthcare", "Python"], visibility: "private",
      files: 18, milestones: 4, completedMilestones: 2
    },
    {
      id: 2, title: "Smart Campus Navigation App", status: "active",
      description: "Indoor navigation system for UIU campus using BLE beacons and AR overlays.",
      domain: "Mobile / IoT", members: [3, 2, 8], progress: 40,
      tasks: { todo: 8, inProgress: 4, done: 7 }, deadline: "2025-07-15",
      tags: ["React Native", "IoT", "AR"], visibility: "public",
      files: 11, milestones: 3, completedMilestones: 1
    },
    {
      id: 3, title: "Blockchain-Based Credential Verification", status: "active",
      description: "Tamper-proof academic credential system using Ethereum smart contracts.",
      domain: "Blockchain", members: [8, 4, 1], progress: 30,
      tasks: { todo: 10, inProgress: 2, done: 5 }, deadline: "2025-08-01",
      tags: ["Blockchain", "Solidity", "Web3"], visibility: "public",
      files: 7, milestones: 5, completedMilestones: 1
    },
    {
      id: 4, title: "NLP-Powered Research Summarizer", status: "completed",
      description: "Automated academic paper summarization using transformer models.",
      domain: "NLP / AI", members: [1, 5, 7], progress: 100,
      tasks: { todo: 0, inProgress: 0, done: 22 }, deadline: "2025-03-01",
      tags: ["NLP", "BERT", "Python"], visibility: "public",
      files: 26, milestones: 4, completedMilestones: 4
    },
  ],

  /*  Tasks for workspace (project 1)  */
  tasks: {
    todo: [
      { id: 1, title: "Collect chest X-ray dataset from NIH", priority: "high", assignee: 7, due: "May 20" },
      { id: 2, title: "Write preprocessing pipeline docs", priority: "medium", assignee: 1, due: "May 25" },
      { id: 3, title: "Research augmentation techniques", priority: "low", assignee: 5, due: "May 28" },
    ],
    inProgress: [
      { id: 4, title: "Train CNN baseline model", priority: "high", assignee: 5, due: "May 18" },
      { id: 5, title: "Implement DICOM reader module", priority: "medium", assignee: 1, due: "May 22" },
    ],
    done: [
      { id: 6, title: "Project setup and repository init", priority: "medium", assignee: 1, due: "Apr 10" },
      { id: 7, title: "Literature review — 20 papers", priority: "high", assignee: 7, due: "Apr 20" },
      { id: 8, title: "Define model architecture", priority: "high", assignee: 5, due: "Apr 28" },
    ]
  },

  /*  Resources  */
  resources: [
    { id: 1, title: "Deep Learning for Medical Image Analysis", type: "Paper", category: "AI/ML", author: "Rafsan Ahmed", downloads: 142, rating: 4.8, tags: ["CNN", "Healthcare", "PyTorch"], size: "2.4 MB", date: "2025-04-01" },
    { id: 2, title: "Introduction to Transformer Models", type: "Study Material", category: "NLP", author: "Nusrat Jahan", downloads: 318, rating: 4.9, tags: ["NLP", "BERT", "GPT"], size: "1.8 MB", date: "2025-03-15" },
    { id: 3, title: "Smart Grid Optimization Techniques", type: "PDF", category: "EEE", author: "Farhan Kabir", downloads: 89, rating: 4.5, tags: ["IoT", "Power Systems"], size: "3.1 MB", date: "2025-03-28" },
    { id: 4, title: "Blockchain Fundamentals & Use Cases", type: "Study Material", category: "CS", author: "Imran Chowdhury", downloads: 205, rating: 4.7, tags: ["Blockchain", "Web3", "DeFi"], size: "4.2 MB", date: "2025-02-10" },
    { id: 5, title: "React 18 Best Practices Guide", type: "Tutorial", category: "Web Dev", author: "Tanvir Hossain", downloads: 456, rating: 4.9, tags: ["React", "JavaScript", "Frontend"], size: "1.2 MB", date: "2025-04-12" },
    { id: 6, title: "Statistical Methods in Research", type: "PDF", category: "Statistics", author: "Sadia Islam", downloads: 167, rating: 4.6, tags: ["Statistics", "Research Methods", "SPSS"], size: "5.7 MB", date: "2025-01-20" },
  ],

  /*  Datasets  */
  datasets: [
    { id: 1, title: "UIU Campus Air Quality Dataset 2024", type: "Dataset", author: "Maliha Rahman", size: "45 MB", downloads: 78, stars: 23, license: "CC BY 4.0", tags: ["IoT", "Environment", "CSV"], version: "v2.1", description: "Hourly air quality readings from 12 sensors across UIU campus for 2024." },
    { id: 2, title: "Chest X-Ray Preprocessing Scripts", type: "Code", author: "Rafsan Ahmed", size: "1.2 MB", downloads: 156, stars: 41, license: "MIT", tags: ["Python", "Medical Imaging", "DICOM"], version: "v1.3", description: "Python scripts to preprocess and augment chest X-ray images from the NIH dataset." },
    { id: 3, title: "Bangla NLP Text Corpus (UIU Edition)", type: "Dataset", author: "Nusrat Jahan", size: "120 MB", downloads: 234, stars: 67, license: "Research Only", tags: ["NLP", "Bengali", "Text"], version: "v3.0", description: "Curated Bangla text corpus from news, social media, and academic sources." },
    { id: 4, title: "Smart Grid Simulation Results", type: "Experiment", author: "Farhan Kabir", size: "22 MB", downloads: 45, stars: 15, license: "CC BY-SA 4.0", tags: ["MATLAB", "Power Systems", "Simulation"], version: "v1.0", description: "MATLAB Simulink results for grid optimization experiments with variable load profiles." },
  ],

  /*  Blog posts  */
  blogs: [
    { id: 1, title: "How We Built a COVID-19 Pattern Analyzer in 3 Weeks", author: 7, category: "Research Summary", readTime: "8 min", likes: 87, comments: 14, tags: ["COVID", "Data Science", "Python"], date: "2025-04-28", excerpt: "Our team of three tackled an ambitious project: building an end-to-end COVID data analyzer. Here's everything we learned, from data collection to deployment..." },
    { id: 2, title: "Getting Started with PyTorch for Computer Vision", author: 5, category: "Tutorial", readTime: "12 min", likes: 134, comments: 28, tags: ["PyTorch", "Computer Vision", "Tutorial"], date: "2025-04-20", excerpt: "Computer vision is one of the most exciting fields in AI. In this tutorial, I'll walk you through building your first image classifier using PyTorch..." },
    { id: 3, title: "Why Every CS Student Should Learn Blockchain (Even if You're Not into Crypto)", author: 8, category: "Opinion", readTime: "6 min", likes: 52, comments: 19, tags: ["Blockchain", "Web3", "Career"], date: "2025-04-15", excerpt: "Blockchain is far more than Bitcoin. Understanding the underlying technology opens doors to smart contracts, supply chain, healthcare records, and more..." },
    { id: 4, title: "A Beginner's Guide to Writing a Research Paper", author: 1, category: "Tutorial", readTime: "10 min", likes: 201, comments: 43, tags: ["Research", "Academic Writing", "IEEE"], date: "2025-04-10", excerpt: "Writing your first research paper is daunting. After struggling through my own first paper, I put together this guide to help fellow students navigate the process..." },
    { id: 5, title: "Indoor Navigation with BLE Beacons: Lessons Learned", author: 3, category: "Research Summary", readTime: "9 min", likes: 76, comments: 21, tags: ["IoT", "BLE", "Mobile"], date: "2025-04-05", excerpt: "Building an indoor navigation system sounds straightforward until you encounter beacon interference, multipath effects, and battery constraints..." },
  ],

  /*  Research ideas  */
  ideas: [
    { id: 1, title: "AI-Based Traffic Signal Optimization for Dhaka", author: 5, upvotes: 134, comments: 28, domain: "AI / Smart City", skills: ["Computer Vision", "Reinforcement Learning", "Python"], status: "Open", description: "Using live camera feeds and RL to dynamically optimize traffic signal timing and reduce congestion in Dhaka city.", date: "2025-04-25" },
    { id: 2, title: "Bangla Sign Language Recognition App", author: 7, upvotes: 98, comments: 19, domain: "NLP / Accessibility", skills: ["Computer Vision", "MediaPipe", "Mobile Dev"], status: "Open", description: "A smartphone app that translates Bangla sign language gestures into text/speech in real time.", date: "2025-04-20" },
    { id: 3, title: "University Food Waste Reduction System", author: 4, upvotes: 61, comments: 11, domain: "IoT / Sustainability", skills: ["IoT", "Data Analysis", "App Dev"], status: "In Progress", description: "Sensor-based food waste tracking in UIU canteen with predictive ordering suggestions.", date: "2025-04-18" },
    { id: 4, title: "Federated Learning for Hospital Networks", author: 1, upvotes: 87, comments: 22, domain: "AI / Healthcare", skills: ["Federated Learning", "Privacy", "Python"], status: "Open", description: "Train ML models across multiple hospitals without sharing patient data — preserving privacy while improving accuracy.", date: "2025-04-15" },
    { id: 5, title: "Student Mental Health Chatbot for UIU", author: 2, upvotes: 145, comments: 35, domain: "NLP / Mental Health", skills: ["NLP", "Chatbot", "Psychology"], status: "Open", description: "An empathetic AI chatbot specifically designed for UIU students to provide mental health support and resource guidance.", date: "2025-04-12" },
  ],

  /*  Events  */
  events: [
    { id: 1, title: "UIU National Hackathon 2025", type: "Hackathon", date: "2025-06-14", endDate: "2025-06-15", location: "UIU Campus", prize: "৳2,00,000", participants: 320, spots: 500, tags: ["AI", "Web", "IoT"], status: "open", organizer: "UIU CSE Department" },
    { id: 2, title: "ICPC Asia Dhaka Regional 2025", type: "Competition", date: "2025-07-20", location: "BUET, Dhaka", prize: "ACM-ICPC Trophy", participants: 180, spots: 300, tags: ["Competitive Programming"], status: "open", organizer: "ACM Bangladesh" },
    { id: 3, title: "Research Methodology Workshop", type: "Workshop", date: "2025-05-28", location: "UIU Auditorium", price: "Free", participants: 65, spots: 100, tags: ["Research", "Academic Writing", "IEEE"], status: "open", organizer: "UIU Research Cell" },
    { id: 4, title: "International Conference on AI (ICAI 2025)", type: "Conference", date: "2025-08-05", endDate: "2025-08-07", location: "Virtual + Dhaka", participants: 890, spots: 1200, tags: ["AI", "ML", "Deep Learning"], status: "open", organizer: "IEEE Bangladesh" },
    { id: 5, title: "Startup Pitch Competition — TechNext BD", type: "Competition", date: "2025-06-01", location: "ICT Division, Dhaka", prize: "৳5,00,000 + Investment", participants: 48, spots: 60, tags: ["Startup", "Innovation", "Fintech"], status: "closing-soon", organizer: "ICT Division, Bangladesh" },
  ],

  /*  Messages  */
  conversations: [
    { id: 1, type: "group", name: "AI Healthcare Team", members: [1,5,7], lastMessage: "Rafsan: Let's sync at 7PM today", time: "2m ago", unread: 3 },
    { id: 2, type: "dm", with: 5, lastMessage: "Can you push the model weights?", time: "18m ago", unread: 1 },
    { id: 3, type: "dm", with: 2, lastMessage: "I saw your idea on the marketplace!", time: "1h ago", unread: 0 },
    { id: 4, type: "group", name: "Smart Campus Project", members: [3,2,8,1], lastMessage: "Tanvir: PR is ready for review", time: "3h ago", unread: 0 },
    { id: 5, type: "dm", with: 8, lastMessage: "Blockchain deployment went live ", time: "Yesterday", unread: 0 },
  ],

  /*  Notifications  */
  notifications: [
    { id: 1, type: "collab",   icon: "collab",   text: "Nusrat Jahan sent you a collaboration request", time: "5 minutes ago", read: false },
    { id: 2, type: "project",  icon: "project",  text: "New task assigned: Train CNN baseline model", time: "1 hour ago", read: false },
    { id: 3, type: "message",  icon: "message",  text: "Rakibul mentioned you in AI Healthcare Team", time: "2 hours ago", read: false },
    { id: 4, type: "idea",     icon: "idea",     text: "Your idea received 10 new upvotes", time: "3 hours ago", read: true },
    { id: 5, type: "blog",     icon: "blog",     text: "Sadia liked your blog post", time: "5 hours ago", read: true },
    { id: 6, type: "resource", icon: "resource", text: "Your dataset was downloaded 50 times today", time: "Yesterday", read: true },
    { id: 7, type: "event",    icon: "event",    text: "UIU National Hackathon registration closes in 3 days", time: "Yesterday", read: true },
    { id: 8, type: "system",   icon: "system",   text: "You earned the Active Researcher badge", time: "2 days ago", read: true },
  ],

  /*  Leaderboard  */
  leaderboard: [
    { rank: 1, student: 5, points: 4100, change: "up" },
    { rank: 2, student: 2, points: 3200, change: "same" },
    { rank: 3, student: 3, points: 2980, change: "up" },
    { rank: 4, student: 1, points: 2840, change: "down" },
    { rank: 5, student: 8, points: 2650, change: "up" },
    { rank: 6, student: 7, points: 2300, change: "down" },
    { rank: 7, student: 4, points: 2100, change: "same" },
    { rank: 8, student: 6, points: 1650, change: "up" },
  ],

  /*  Contribution data (for charts)  */
  contributions: {
    projectId: 1,
    members: [
      { studentId: 1, edits: 42, uploads: 8, tasks: 12, comments: 28, score: 90 },
      { studentId: 5, edits: 38, uploads: 5, tasks: 15, comments: 19, score: 87 },
      { studentId: 7, edits: 24, uploads: 12, tasks:  9, comments: 14, score: 73 },
    ]
  },

  /*  Reputation points breakdown  */
  reputationPoints: [
    { action: "Project Completed (NLP Summarizer)", points: 500, date: "2025-03-01" },
    { action: "Resource Shared (10+ downloads)", points: 50, date: "2025-03-10" },
    { action: "Blog Published (50+ likes)", points: 100, date: "2025-04-10" },
    { action: "Helped peer resolve issue", points: 25, date: "2025-04-15" },
    { action: "Idea upvoted 50+ times", points: 75, date: "2025-04-20" },
    { action: "Dataset downloaded 100+ times", points: 100, date: "2025-04-25" },
  ],

  /*  Badges  */
  badges: [
    { id: 1, name: "Active Researcher",  icon: "AR", color: "accent", earned: true,  desc: "Contributed to 5+ projects" },
    { id: 2, name: "Top Contributor",    icon: "TC", color: "orange", earned: true,  desc: "Ranked top 10 in contributions" },
    { id: 3, name: "Knowledge Sharer",   icon: "KS", color: "purple", earned: true,  desc: "Shared 3+ quality resources" },
    { id: 4, name: "Idea Innovator",     icon: "II", color: "teal",   earned: true,  desc: "Posted an idea with 50+ upvotes" },
    { id: 5, name: "Team Player",        icon: "TP", color: "green",  earned: false, desc: "Completed 3 team projects" },
    { id: 6, name: "Research Pioneer",   icon: "RP", color: "accent", earned: false, desc: "Published a research paper" },
    { id: 7, name: "Data Champion",      icon: "DC", color: "purple", earned: false, desc: "Shared 5+ datasets" },
    { id: 8, name: "Community Leader",   icon: "CL", color: "orange", earned: false, desc: "Reach top 3 on leaderboard" },
  ],

  /*  Helper functions  */
  getStudent(id) { return this.students.find(s => s.id === id) || this.currentUser; },
  getProject(id) { return this.projects.find(p => p.id === id); },

  priorityColor: { high: "red", medium: "orange", low: "green" },
  typeColors: { "Paper": "accent", "Study Material": "purple", "PDF": "green", "Tutorial": "teal", "Code": "orange", "Dataset": "accent", "Experiment": "purple" },
  eventTypeColors: { "Hackathon": "accent", "Competition": "orange", "Workshop": "purple", "Conference": "green" },
};
