import os
import shutil
import customtkinter as ctk
from tkinter import filedialog, messagebox, ttk
import matplotlib.pyplot as plt
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg
from datetime import datetime
import time
from threading import Thread
import webbrowser
from PIL import Image, ImageTk
import numpy as np
from matplotlib.colors import LinearSegmentedColormap
import pandas as pd
from collections import defaultdict
import json
import sys
from matplotlib import cm
import zipfile
import tkinter as tk
from tkinter import simpledialog
import random
import sys
import traceback
import logging

logging.basicConfig(
    filename='NexusFile_Pro.log',
    level=logging.ERROR,
    format='%(asctime)s - %(levelname)s - %(message)s'
)

def handle_exception(exc_type, exc_value, exc_traceback):
    logging.error("Uncaught exception", exc_info=(exc_type, exc_value, exc_traceback))
    sys._excepthook_(exc_type, exc_value, exc_traceback)

sys.excepthook = handle_exception

# Fix for PyInstaller
if getattr(sys, 'frozen', False):
    os.environ['TKINTER_DND_PATH'] = os.path.join(sys._MEIPASS, 'tkdnd')

# Set modern appearance
ctk.set_appearance_mode("System")
ctk.set_default_color_theme("blue")

# Advanced file categorization with nested extensions
FILE_TYPES = {
    'Images': {
        'Raster': ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp'],
        'Vector': ['.svg', '.ai', '.eps'],
        'RAW': ['.cr2', '.nef', '.dng', '.arw']
    },
    'Videos': {
        'Standard': ['.mp4', '.mov', '.avi'],
        'HD': ['.mkv', '.flv', '.wmv'],
        'Professional': ['.prores', '.r3d', '.ari']
    },
    'Documents': {
        'PDFs': ['.pdf'],
        'Word': ['.docx', '.doc', '.odt'],
        'Excel': ['.xls', '.xlsx', '.csv', '.ods'],
        'PowerPoint': ['.pptx', '.ppt', '.odp'],
        'Text': ['.txt', '.rtf', '.md']
    },
    'Audio': {
        'Compressed': ['.mp3', '.aac', '.ogg'],
        'Lossless': ['.wav', '.flac', '.alac']
    },
    'Archives': {
        'Compressed': ['.zip', '.rar', '.7z'],
        'DiskImages': ['.iso', '.dmg', '.img'],
        'Packages': ['.tar', '.gz', '.bz2']
    },
    'Executables': {
        'Windows': ['.exe', '.msi', '.bat'],
        'Mac': ['.app', '.dmg', '.pkg'],
        'Linux': ['.deb', '.rpm', '.sh']
    },
    'Code': {
        'Web': ['.html', '.css', '.js', '.php'],
        'Python': ['.py', '.pyc', '.pyd'],
        'Java': ['.java', '.class', '.jar'],
        'C/C++': ['.c', '.cpp', '.h', '.o']
    },
    'Data': {
        'Database': ['.db', '.sql', '.sqlite'],
        'JSON/XML': ['.json', '.xml', '.yaml']
    },
    'Others': {}
}

# Chatbot knowledge base
CHATBOT_KNOWLEDGE = {
    "how to use": "To use this software:\n1. Select a folder using the Browse button\n2. Choose organization options in Settings\n3. Click 'Organize Files' to start\n4. Use the chatbot for any questions!",
    "organize files": "The software can organize files by:\n- Category (default)\n- Date\n- Alphabetical order\n- File size\n- Custom rules (set in Settings)",
    "settings": "You can customize:\n- Appearance (Light/Dark mode)\n- Organization method\n- Folder naming\n- File handling options\n- Compression settings",
    "problems": "Common solutions:\n1. Make sure you have permission to access the folder\n2. Check if files aren't in use by other programs\n3. Try organizing smaller batches of files\n4. Restart the application",
    "features": "Main features:\n- Smart file organization\n- File compression\n- Custom folder naming\n- Visual statistics\n- Chatbot assistance\n- Dark/Light mode\n- File unorganizing",
    "compression": "To compress files:\n1. Select files/folders in Settings\n2. Choose compression format (ZIP, RAR, etc.)\n3. Set compression level\n4. Click 'Compress' button",
    "unorganize": "To unorganize:\n1. Select the organized folder\n2. Click 'Unorganize' button\n3. All files will be moved to root folder\n4. Empty subfolders will be deleted",
    "help": "I can help with:\n- Software usage\n- Troubleshooting\n- Feature explanations\n- Settings configuration\nType your question and I'll try to help!",
    "about": "NexusFile Pro Advanced\nVersion 5.0\nDeveloped by Muhammad Uzair & Rifaq Ajmal\nAll rights reserved © 2023",
    "hi": "Hello! How can I assist you with NexusFile Pro today?",
    "hello": "Hi there! What can I help you with regarding the file organizer?",
    "thanks": "You're welcome! Let me know if you need anything else.",
    "thank you": "My pleasure! Feel free to ask if you have more questions."
}

class ChatbotWindow(ctk.CTkToplevel):
    def __init__(self, master, **kwargs):
        super().__init__(master, **kwargs)
        self.title("NexusFile Assistant")
        self.geometry("500x600")
        self.minsize(400, 500)
        
        # Configure grid
        self.grid_columnconfigure(0, weight=1)
        self.grid_rowconfigure(1, weight=1)
        
        # Header
        self.header = ctk.CTkFrame(self, fg_color="transparent")
        self.header.grid(row=0, column=0, padx=10, pady=10, sticky="nsew")
        
        self.chatbot_icon = ctk.CTkImage(light_image=Image.new("RGBA", (32, 32), (0, 0, 0, 0)),
                                        dark_image=Image.new("RGBA", (32, 32), (255, 255, 255, 0)),
                                        size=(32, 32))
        self.icon_label = ctk.CTkLabel(self.header, image=self.chatbot_icon, text="")
        self.icon_label.pack(side="left", padx=(0, 10))
        
        self.title_label = ctk.CTkLabel(self.header, 
                                      text="NexusFile Assistant", 
                                      font=ctk.CTkFont(size=16, weight="bold"))
        self.title_label.pack(side="left", fill="x", expand=True)
        
        # Chat display
        self.chat_frame = ctk.CTkFrame(self)
        self.chat_frame.grid(row=1, column=0, padx=10, pady=(0, 10), sticky="nsew")
        self.chat_frame.grid_columnconfigure(0, weight=1)
        self.chat_frame.grid_rowconfigure(0, weight=1)
        
        self.chat_text = ctk.CTkTextbox(self.chat_frame, wrap="word", state="disabled")
        self.chat_text.grid(row=0, column=0, sticky="nsew")
        
        # Input area
        self.input_frame = ctk.CTkFrame(self, fg_color="transparent")
        self.input_frame.grid(row=2, column=0, padx=10, pady=(0, 10), sticky="nsew")
        self.input_frame.grid_columnconfigure(0, weight=1)
        
        self.user_input = ctk.CTkEntry(self.input_frame, placeholder_text="Type your question here...")
        self.user_input.grid(row=0, column=0, sticky="ew", padx=(0, 5))
        self.user_input.bind("<Return>", lambda e: self.process_input())
        
        self.send_btn = ctk.CTkButton(self.input_frame, text="Send", width=80, command=self.process_input)
        self.send_btn.grid(row=0, column=1)
        
        # Footer
        self.footer = ctk.CTkLabel(self, 
                                 text="Designed By Muhammad Uzair & Rifaq Ajmal", 
                                 font=("Segoe UI", 10), 
                                 text_color=("gray40", "gray60"))
        self.footer.grid(row=3, column=0, pady=(0, 10))
        
        # Initial greeting
        self.add_message("assistant", "Hello! I'm NexusFile Assistant. How can I help you today?")
        
    def add_message(self, sender, message):
        self.chat_text.configure(state="normal")
        tag = "user" if sender == "user" else "assistant"
        color = "#2b7bb9" if sender == "user" else "#4CAF50"
        
        self.chat_text.insert("end", f"{sender.capitalize()}: ", (tag, "bold"))
        self.chat_text.insert("end", f"{message}\n\n", tag)
        
        self.chat_text.tag_config(tag, foreground=color)
        self.chat_text.tag_config("bold", font=("Arial", 10, "bold"))
        
        self.chat_text.see("end")
        self.chat_text.configure(state="disabled")
    
    def process_input(self, event=None):
        user_text = self.user_input.get().strip().lower()
        if not user_text:
            return
            
        self.add_message("user", user_text)
        self.user_input.delete(0, "end")
        
        # Find best matching response
        response = "I'm not sure I understand. Could you rephrase your question?"
        for keyword, answer in CHATBOT_KNOWLEDGE.items():
            if keyword in user_text:
                response = answer
                break
        
        # Simulate typing effect
        self.after(500, lambda: self.add_message("assistant", response))

class SettingsWindow(ctk.CTkToplevel):
    def __init__(self, master, **kwargs):
        super().__init__(master, **kwargs)
        self.title("Settings")
        self.geometry("600x500")
        self.minsize(500, 400)
        
        # Configure grid
        self.grid_columnconfigure(0, weight=1)
        self.grid_rowconfigure(1, weight=1)
        
        # Tabs
        self.tabview = ctk.CTkTabview(self)
        self.tabview.grid(row=0, column=0, padx=10, pady=(10, 0), sticky="nsew")
        
        # Appearance Tab
        self.appearance_tab = self.tabview.add("Appearance")
        
        self.theme_label = ctk.CTkLabel(self.appearance_tab, text="Color Theme:")
        self.theme_label.pack(pady=(10, 5))
        
        self.theme_var = ctk.StringVar(value=master.appearance_mode.get())
        self.theme_menu = ctk.CTkOptionMenu(self.appearance_tab, 
                                          values=["Light", "Dark", "System"],
                                          variable=self.theme_var,
                                          command=self.change_theme)
        self.theme_menu.pack(pady=(0, 10))
        
        # Organization Tab
        self.org_tab = self.tabview.add("Organization")
        
        self.org_method_label = ctk.CTkLabel(self.org_tab, text="Organization Method:")
        self.org_method_label.pack(pady=(10, 5))
        
        self.org_method_var = ctk.StringVar(value="category")
        self.org_method_menu = ctk.CTkOptionMenu(self.org_tab, 
                                               values=["Category", "Date", "Alphabetical", "Size", "Custom"],
                                               variable=self.org_method_var)
        self.org_method_menu.pack(pady=(0, 10))
        
        self.custom_folder_label = ctk.CTkLabel(self.org_tab, text="Custom Folder Names:")
        self.custom_folder_label.pack(pady=(10, 5))
        
        self.custom_folder_entry = ctk.CTkEntry(self.org_tab, placeholder_text="Enter folder naming pattern")
        self.custom_folder_entry.pack(pady=(0, 10))
        
        # Compression Tab
        self.comp_tab = self.tabview.add("Compression")
        
        self.comp_format_label = ctk.CTkLabel(self.comp_tab, text="Compression Format:")
        self.comp_format_label.pack(pady=(10, 5))
        
        self.comp_format_var = ctk.StringVar(value="zip")
        self.comp_format_menu = ctk.CTkOptionMenu(self.comp_tab, 
                                                values=["ZIP", "RAR", "7Z", "TAR"],
                                                variable=self.comp_format_var)
        self.comp_format_menu.pack(pady=(0, 10))
        
        self.comp_level_label = ctk.CTkLabel(self.comp_tab, text="Compression Level:")
        self.comp_level_label.pack(pady=(10, 5))
        
        self.comp_level_slider = ctk.CTkSlider(self.comp_tab, from_=1, to=9, number_of_steps=8)
        self.comp_level_slider.set(6)
        self.comp_level_slider.pack(pady=(0, 10))
        
        # Footer
        self.footer = ctk.CTkLabel(self, 
                                 text="Designed By Muhammad Uzair & Rifaq Ajmal", 
                                 font=("Segoe UI", 10), 
                                 text_color=("gray40", "gray60"))
        self.footer.grid(row=2, column=0, pady=(0, 10))
        
    def change_theme(self, choice):
        self.master.set_appearance_mode(choice)

class AdvancedGraphFrame(ctk.CTkFrame):
    def __init__(self, master, **kwargs):
        super().__init__(master, **kwargs)
        
        # Create tabs for different graph types
        self.tabview = ctk.CTkTabview(self)
        self.tabview.pack(fill="both", expand=True)
        
        # Pie Chart Tab
        self.pie_tab = self.tabview.add("Pie Chart")
        self.figure_pie, self.ax_pie = plt.subplots(figsize=(6, 4), dpi=100)
        self.canvas_pie = FigureCanvasTkAgg(self.figure_pie, self.pie_tab)
        self.canvas_pie.get_tk_widget().pack(fill="both", expand=True)
        
        # Bar Chart Tab
        self.bar_tab = self.tabview.add("Bar Chart")
        self.figure_bar, self.ax_bar = plt.subplots(figsize=(6, 4), dpi=100)
        self.canvas_bar = FigureCanvasTkAgg(self.figure_bar, self.bar_tab)
        self.canvas_bar.get_tk_widget().pack(fill="both", expand=True)
        
        # Heatmap Tab
        self.heatmap_tab = self.tabview.add("Heatmap")
        self.figure_heat, self.ax_heat = plt.subplots(figsize=(6, 4), dpi=100)
        self.canvas_heat = FigureCanvasTkAgg(self.figure_heat, self.heatmap_tab)
        self.canvas_heat.get_tk_widget().pack(fill="both", expand=True)
        
        # Configure styles
        self.configure_graph_styles()
    
    def configure_graph_styles(self):
        # Use matplotlib's built-in color palettes
        self.palette = plt.cm.tab10.colors  # 10-color palette
        self.cmap = cm.viridis  # Color map for gradients
    
    def update_graphs(self, file_counts, appearance_mode):
        # Prepare data
        categories = [k for k, v in file_counts.items() if v > 0]
        counts = [v for v in file_counts.values() if v > 0]
        
        if not categories:
            self.show_empty_graphs()
            return
        
        # Set theme based on appearance mode
        bg_color = '#2b2b2b' if appearance_mode == "Dark" else 'white'
        text_color = 'white' if appearance_mode == "Dark" else 'black'
        
        # Update Pie Chart
        self.update_pie_chart(categories, counts, bg_color, text_color)
        
        # Update Bar Chart
        self.update_bar_chart(categories, counts, bg_color, text_color)
        
        # Update Heatmap
        self.update_heatmap(categories, counts, bg_color, text_color)
    
    def update_pie_chart(self, categories, counts, bg_color, text_color):
        self.ax_pie.clear()
        
        # Create explode effect for the largest segment
        max_idx = counts.index(max(counts))
        explode = [0.1 if i == max_idx else 0 for i in range(len(categories))]
        
        # Create pie chart
        wedges, texts, autotexts = self.ax_pie.pie(
            counts, 
            labels=categories, 
            autopct='%1.1f%%',
            startangle=140,
            colors=self.palette,
            explode=explode,
            shadow=True,
            textprops={'color': text_color}
        )
        
        # Style adjustments
        self.ax_pie.set_title('File Type Distribution', color=text_color, pad=20)
        self.figure_pie.patch.set_facecolor(bg_color)
        self.ax_pie.set_facecolor(bg_color)
        
        # Make labels more readable
        for text in texts + autotexts:
            text.set_color(text_color)
            text.set_fontsize(8)
        
        self.canvas_pie.draw()
    
    def update_bar_chart(self, categories, counts, bg_color, text_color):
        self.ax_bar.clear()
        
        # Create bar chart with gradient effect
        bars = self.ax_bar.bar(categories, counts, color=self.palette[0])
        
        # Add gradient to bars
        for bar in bars:
            bar.set_alpha(0.7)
            bar.set_edgecolor('white')
            bar.set_linewidth(0.5)
        
        # Add value labels
        for bar in bars:
            height = bar.get_height()
            self.ax_bar.text(bar.get_x() + bar.get_width()/2., height,
                            f'{int(height)}',
                            ha='center', va='bottom', 
                            color=text_color,
                            fontweight='bold')
        
        # Style adjustments
        self.ax_bar.set_title('File Count by Category', color=text_color, pad=20)
        self.ax_bar.set_ylabel('Number of Files', color=text_color)
        self.ax_bar.tick_params(axis='x', labelrotation=45, colors=text_color)
        self.ax_bar.tick_params(axis='y', colors=text_color)
        
        for spine in self.ax_bar.spines.values():
            spine.set_color(text_color)
        
        self.figure_bar.patch.set_facecolor(bg_color)
        self.ax_bar.set_facecolor(bg_color)
        self.ax_bar.grid(True, linestyle='--', alpha=0.3)
        
        self.canvas_bar.draw()
    
    def update_heatmap(self, categories, counts, bg_color, text_color):
        self.ax_heat.clear()
        
        # Create a grid of values
        data = [[count for count in counts]]
        
        # Create heatmap using matplotlib's imshow
        im = self.ax_heat.imshow(data, cmap=self.cmap, aspect='auto')
        
        # Add colorbar
        cbar = self.figure_heat.colorbar(im, ax=self.ax_heat)
        cbar.set_label('File Count', color=text_color)
        cbar.ax.yaxis.set_tick_params(color=text_color)
        
        # Set labels
        self.ax_heat.set_xticks(np.arange(len(categories)))
        self.ax_heat.set_xticklabels(categories, rotation=45, ha='right', color=text_color)
        self.ax_heat.set_yticks([])  # Hide y-axis
        
        # Add value annotations
        for i in range(len(categories)):
            self.ax_heat.text(i, 0, str(counts[i]),
                            ha='center', va='center',
                            color=text_color,
                            fontweight='bold')
        
        # Style adjustments
        self.ax_heat.set_title('File Distribution', color=text_color, pad=20)
        self.figure_heat.patch.set_facecolor(bg_color)
        self.ax_heat.set_facecolor(bg_color)
        
        self.canvas_heat.draw()
    
    def show_empty_graphs(self):
        text_color = 'black' if ctk.get_appearance_mode() == "Light" else 'white'
        
        for ax in [self.ax_pie, self.ax_bar, self.ax_heat]:
            ax.clear()
            ax.text(0.5, 0.5, 'No files found', 
                   ha='center', va='center', 
                   color=text_color,
                   fontsize=12)
            ax.set_xticks([])
            ax.set_yticks([])
        
        self.canvas_pie.draw()
        self.canvas_bar.draw()
        self.canvas_heat.draw()

class FileOrganizerApp(ctk.CTk):
    def __init__(self):
        super().__init__()
        self.title("NexusFile Pro - Advanced")
        self.geometry("1000x800")
        self.minsize(800, 600)
        
        # Variables
        self.folder_path = ctk.StringVar()
        self.appearance_mode = ctk.StringVar(value="System")
        self.time_string = ctk.StringVar()
        self.date_string = ctk.StringVar()
        self.total_files = ctk.IntVar(value=0)
        self.selected_category = ctk.StringVar(value="All Categories")
        self.organization_method = ctk.StringVar(value="category")
        self.compression_format = ctk.StringVar(value="zip")
        
        # Initialize file count data
        self.file_counts = self.initialize_file_counts()
        self.file_details = defaultdict(list)
        
        # Time and date
        self.update_time_date()
        
        # Setup UI
        self.create_widgets()
        
        # Start time update thread
        self.running = True
        self.time_thread = Thread(target=self.update_time_loop)
        self.time_thread.daemon = True
        self.time_thread.start()
        
        # Bind maximize/minimize events
        self.bind("<F11>", self.toggle_fullscreen)
        self.bind("<Escape>", self.exit_fullscreen)
        
        # State tracking
        self.is_fullscreen = False
        self.last_geometry = None
    
    def initialize_file_counts(self):
        counts = {}
        for main_category, sub_categories in FILE_TYPES.items():
            if sub_categories:  # Has nested categories
                for sub_category in sub_categories:
                    counts[f"{main_category}/{sub_category}"] = 0
            else:
                counts[main_category] = 0
        return counts
    
    def create_widgets(self):
        # Configure grid
        self.grid_columnconfigure(0, weight=1)
        self.grid_rowconfigure(2, weight=1)
        
        # Header frame
        self.header_frame = ctk.CTkFrame(self, fg_color="transparent")
        self.header_frame.grid(row=0, column=0, padx=20, pady=(10, 5), sticky="nsew")
        
        # Title with logo
        self.logo_image = ctk.CTkImage(light_image=Image.new("RGBA", (40, 40), (0, 0, 0, 0)),
                                      dark_image=Image.new("RGBA", (40, 40), (255, 255, 255, 0)),
                                      size=(40, 40))
        self.logo_label = ctk.CTkLabel(self.header_frame, image=self.logo_image, text="")
        self.logo_label.pack(side="left", padx=(0, 10))
        
        self.title_label = ctk.CTkLabel(self.header_frame, 
                                      text="NexusFile Pro - Advanced", 
                                      font=ctk.CTkFont(size=24, weight="bold"))
        self.title_label.pack(side="left")
        
        # Window control buttons
        self.window_controls = ctk.CTkFrame(self.header_frame, fg_color="transparent")
        self.window_controls.pack(side="right")
        
        self.minimize_btn = ctk.CTkButton(self.window_controls, 
                                         text="_", 
                                         width=30, 
                                         height=30,
                                         command=self.iconify)
        self.minimize_btn.pack(side="left", padx=2)
        
        self.maximize_btn = ctk.CTkButton(self.window_controls, 
                                        text="□", 
                                        width=30, 
                                        height=30,
                                        command=self.toggle_maximize)
        self.maximize_btn.pack(side="left", padx=2)
        
        self.close_btn = ctk.CTkButton(self.window_controls, 
                                     text="×", 
                                     width=30, 
                                     height=30,
                                     fg_color="#e74c3c",
                                     hover_color="#c0392b",
                                     command=self.on_closing)
        self.close_btn.pack(side="left", padx=2)
        
        # Toolbar with additional buttons
        self.toolbar_frame = ctk.CTkFrame(self.header_frame, fg_color="transparent")
        self.toolbar_frame.pack(side="right", padx=20)
        
        self.chatbot_btn = ctk.CTkButton(self.toolbar_frame, 
                                        text="", 
                                        width=30, 
                                        height=30,
                                        command=self.open_chatbot)
        self.chatbot_btn.pack(side="left", padx=2)
        
        self.settings_btn = ctk.CTkButton(self.toolbar_frame, 
                                         text="⚙", 
                                         width=30, 
                                         height=30,
                                         command=self.open_settings)
        self.settings_btn.pack(side="left", padx=2)
        
        # Info bar (time, date, theme)
        self.info_frame = ctk.CTkFrame(self, fg_color="transparent")
        self.info_frame.grid(row=1, column=0, padx=20, pady=(0, 10), sticky="nsew")
        
        self.time_label = ctk.CTkLabel(self.info_frame, 
                                     textvariable=self.time_string, 
                                     font=("Arial", 14))
        self.time_label.pack(side="left", padx=5)
        
        self.date_label = ctk.CTkLabel(self.info_frame, 
                                     textvariable=self.date_string, 
                                     font=("Arial", 14))
        self.date_label.pack(side="left", padx=5)
        
        # Theme switcher
        self.theme_menu = ctk.CTkOptionMenu(self.info_frame, 
                                           values=["Light", "Dark", "System"],
                                           variable=self.appearance_mode,
                                           command=self.set_appearance_mode)
        self.theme_menu.pack(side="right")
        
        # Main content area
        self.main_frame = ctk.CTkFrame(self)
        self.main_frame.grid(row=2, column=0, padx=20, pady=(0, 10), sticky="nsew")
        self.main_frame.grid_columnconfigure(0, weight=1)
        self.main_frame.grid_rowconfigure(1, weight=1)
        
        # Top controls
        self.controls_frame = ctk.CTkFrame(self.main_frame, fg_color="transparent")
        self.controls_frame.grid(row=0, column=0, sticky="nsew")
        
        # Folder selection
        self.folder_frame = ctk.CTkFrame(self.controls_frame, fg_color="transparent")
        self.folder_frame.pack(side="left", fill="x", expand=True)
        
        self.folder_entry = ctk.CTkEntry(self.folder_frame, 
                                        textvariable=self.folder_path, 
                                        placeholder_text="Select a folder or drag it here...")
        self.folder_entry.pack(side="left", fill="x", expand=True, padx=(0, 5))
        
        self.browse_btn = ctk.CTkButton(self.folder_frame, 
                                       text="Browse", 
                                       width=80, 
                                       command=self.browse_folder)
        self.browse_btn.pack(side="left")
        
        # Action buttons
        self.action_frame = ctk.CTkFrame(self.controls_frame, fg_color="transparent")
        self.action_frame.pack(side="right")
        
        self.organize_btn = ctk.CTkButton(self.action_frame, 
                                         text="Organize", 
                                         width=100,
                                         command=self.start_organizing)
        self.organize_btn.pack(side="left", padx=2)
        
        self.unorganize_btn = ctk.CTkButton(self.action_frame, 
                                          text="Unorganize", 
                                          width=100,
                                          command=self.unorganize_files)
        self.unorganize_btn.pack(side="left", padx=2)
        
        self.compress_btn = ctk.CTkButton(self.action_frame, 
                                        text="Compress", 
                                        width=100,
                                        command=self.compress_files)
        self.compress_btn.pack(side="left", padx=2)
        
        # Progress bar
        self.progress_bar = ctk.CTkProgressBar(self.controls_frame)
        self.progress_bar.set(0)
        self.progress_bar.pack(side="right", fill="x", expand=True, padx=5)
        
        # File info display
        self.info_display = ctk.CTkFrame(self.main_frame)
        self.info_display.grid(row=1, column=0, sticky="nsew", pady=(5, 0))
        self.info_display.grid_columnconfigure(0, weight=1)
        self.info_display.grid_rowconfigure(0, weight=1)
        
        # Tabbed interface
        self.tabview = ctk.CTkTabview(self.info_display)
        self.tabview.grid(row=0, column=0, sticky="nsew")
        
        # Graph tab
        self.graph_tab = self.tabview.add("Visualization")
        self.graph_frame = AdvancedGraphFrame(self.graph_tab)
        self.graph_frame.pack(fill="both", expand=True)
        
        # Details tab
        self.details_tab = self.tabview.add("File Details")
        self.tree_frame = ctk.CTkFrame(self.details_tab)
        self.tree_frame.pack(fill="both", expand=True)
        
        self.tree = ttk.Treeview(self.tree_frame, columns=("Category", "Count", "Size"), show="headings")
        self.tree.heading("Category", text="Category")
        self.tree.heading("Count", text="File Count")
        self.tree.heading("Size", text="Total Size")
        
        vsb = ttk.Scrollbar(self.tree_frame, orient="vertical", command=self.tree.yview)
        hsb = ttk.Scrollbar(self.tree_frame, orient="horizontal", command=self.tree.xview)
        self.tree.configure(yscrollcommand=vsb.set, xscrollcommand=hsb.set)
        
        self.tree.grid(row=0, column=0, sticky="nsew")
        vsb.grid(row=0, column=1, sticky="ns")
        hsb.grid(row=1, column=0, sticky="ew")
        
        self.tree_frame.grid_columnconfigure(0, weight=1)
        self.tree_frame.grid_rowconfigure(0, weight=1)
        
        # Footer
        self.footer = ctk.CTkLabel(self, 
                                 text="Designed By Muhammad Uzair & Rifaq Ajmal", 
                                 font=("Segoe UI", 10), 
                                 text_color=("gray40", "gray60"))
        self.footer.grid(row=3, column=0, pady=(0, 10))
    
    def open_chatbot(self):
        if not hasattr(self, 'chatbot_window') or not self.chatbot_window.winfo_exists():
            self.chatbot_window = ChatbotWindow(self)
            self.chatbot_window.focus()
        else:
            self.chatbot_window.focus()
    
    def open_settings(self):
        if not hasattr(self, 'settings_window') or not self.settings_window.winfo_exists():
            self.settings_window = SettingsWindow(self)
            self.settings_window.focus()
        else:
            self.settings_window.focus()
    
    def toggle_maximize(self):
        if self.is_fullscreen:
            self.exit_fullscreen()
        else:
            self.maximize_window()
    
    def maximize_window(self):
        self.last_geometry = self.geometry()
        self.state('zoomed')
        self.is_fullscreen = False
    
    def toggle_fullscreen(self, event=None):
        if self.is_fullscreen:
            self.exit_fullscreen()
        else:
            self.enter_fullscreen()
    
    def enter_fullscreen(self):
        self.last_geometry = self.geometry()
        self.attributes('-fullscreen', True)
        self.is_fullscreen = True
    
    def exit_fullscreen(self, event=None):
        self.attributes('-fullscreen', False)
        if self.last_geometry:
            self.geometry(self.last_geometry)
        self.is_fullscreen = False
    
    def update_time_loop(self):
        while self.running:
            self.update_time_date()
            time.sleep(1)
    
    def update_time_date(self):
        now = datetime.now()
        self.time_string.set(now.strftime("%I:%M:%S %p"))
        self.date_string.set(now.strftime("%a, %b %d %Y"))
    
    def set_appearance_mode(self, mode):
        ctk.set_appearance_mode(mode)
        self.appearance_mode.set(mode)
        self.update_graphs()
    
    def browse_folder(self):
        selected = filedialog.askdirectory()
        if selected:
            self.folder_path.set(selected)
            self.scan_folder()
    
    def scan_folder(self):
        folder = self.folder_path.get()
        if not folder or not os.path.exists(folder):
            return
        
        # Reset counts and details
        self.file_counts = self.initialize_file_counts()
        self.file_details = defaultdict(list)
        total_size = 0
        
        # Walk through all files in the folder
        for root, _, files in os.walk(folder):
            for filename in files:
                file_path = os.path.join(root, filename)
                if os.path.isfile(file_path):
                    ext = os.path.splitext(filename)[1].lower()
                    file_size = os.path.getsize(file_path)
                    total_size += file_size
                    
                    # Find the appropriate category
                    category = self.get_file_category(ext)
                    
                    # Update counts and details
                    if category in self.file_counts:
                        self.file_counts[category] += 1
                    else:
                        self.file_counts['Others'] += 1
                        category = 'Others'
                    
                    self.file_details[category].append({
                        'name': filename,
                        'path': file_path,
                        'size': file_size,
                        'modified': os.path.getmtime(file_path)
                    })
        
        self.total_files.set(sum(self.file_counts.values()))
        self.update_file_tree()
        self.update_graphs()
    
    def get_file_category(self, ext):
        for main_category, sub_categories in FILE_TYPES.items():
            if sub_categories:  # Has nested categories
                for sub_category, extensions in sub_categories.items():
                    if ext in extensions:
                        return f"{main_category}/{sub_category}"
            else:  # No nested categories
                if ext in FILE_TYPES[main_category]:
                    return main_category
        return 'Others'
    
    def update_file_tree(self):
        # Clear existing items
        for item in self.tree.get_children():
            self.tree.delete(item)
        
        # Add new items
        for category, count in self.file_counts.items():
            if count > 0:
                size = sum(f['size'] for f in self.file_details.get(category, []))
                size_str = self.format_size(size)
                self.tree.insert("", "end", values=(category, count, size_str))
    
    def format_size(self, size):
        # Convert bytes to human-readable format
        for unit in ['B', 'KB', 'MB', 'GB', 'TB']:
            if size < 1024.0:
                return f"{size:.2f} {unit}"
            size /= 1024.0
        return f"{size:.2f} PB"
    
    def update_graphs(self):
        self.graph_frame.update_graphs(self.file_counts, self.appearance_mode.get())
    
    def start_organizing(self):
        folder = self.folder_path.get()
        if not folder or not os.path.exists(folder):
            messagebox.showerror("Error", "Please select a valid folder first")
            return
        
        # Ask for organization method if not set
        method = self.organization_method.get()
        
        if method == "category":
            self.organize_by_category()
        elif method == "date":
            self.organize_by_date()
        elif method == "alphabetical":
            self.organize_by_alphabet()
        elif method == "size":
            self.organize_by_size()
        else:
            self.organize_by_category()
    
    def organize_by_category(self):
        folder = self.folder_path.get()
        total_files = sum(self.file_counts.values())
        processed = 0
        
        for category, files in self.file_details.items():
            # Create category folder if it doesn't exist
            category_folder = os.path.join(folder, category)
            os.makedirs(category_folder, exist_ok=True)
            
            # Move files to their category folder
            for file_info in files:
                src = file_info['path']
                dst = os.path.join(category_folder, file_info['name'])
                
                try:
                    shutil.move(src, dst)
                    processed += 1
                    progress = processed / total_files
                    self.progress_bar.set(progress)
                    self.update_idletasks()
                except Exception as e:
                    print(f"Error moving {src}: {e}")
        
        messagebox.showinfo("Success", f"Organized {processed} files by category")
        self.progress_bar.set(0)
        self.scan_folder()
    
    def organize_by_date(self):
        folder = self.folder_path.get()
        total_files = sum(self.file_counts.values())
        processed = 0
        
        for files in self.file_details.values():
            for file_info in files:
                # Get modification date
                mod_time = datetime.fromtimestamp(file_info['modified'])
                date_folder = mod_time.strftime("%Y-%m-%d")
                
                # Create date folder if it doesn't exist
                target_folder = os.path.join(folder, date_folder)
                os.makedirs(target_folder, exist_ok=True)
                
                # Move file
                src = file_info['path']
                dst = os.path.join(target_folder, file_info['name'])
                
                try:
                    shutil.move(src, dst)
                    processed += 1
                    progress = processed / total_files
                    self.progress_bar.set(progress)
                    self.update_idletasks()
                except Exception as e:
                    print(f"Error moving {src}: {e}")
        
        messagebox.showinfo("Success", f"Organized {processed} files by date")
        self.progress_bar.set(0)
        self.scan_folder()
    
    def organize_by_alphabet(self):
        folder = self.folder_path.get()
        total_files = sum(self.file_counts.values())
        processed = 0
        
        for files in self.file_details.values():
            for file_info in files:
                # Get first letter of filename
                first_char = file_info['name'][0].upper()
                if not first_char.isalpha():
                    first_char = "0-9"
                
                # Create letter folder if it doesn't exist
                target_folder = os.path.join(folder, first_char)
                os.makedirs(target_folder, exist_ok=True)
                
                # Move file
                src = file_info['path']
                dst = os.path.join(target_folder, file_info['name'])
                
                try:
                    shutil.move(src, dst)
                    processed += 1
                    progress = processed / total_files
                    self.progress_bar.set(progress)
                    self.update_idletasks()
                except Exception as e:
                    print(f"Error moving {src}: {e}")
        
        messagebox.showinfo("Success", f"Organized {processed} files alphabetically")
        self.progress_bar.set(0)
        self.scan_folder()
    
    def organize_by_size(self):
        folder = self.folder_path.get()
        total_files = sum(self.file_counts.values())
        processed = 0
        
        size_categories = {
            "Tiny (<1MB)": (0, 1024*1024),
            "Small (1-10MB)": (1024*1024, 10*1024*1024),
            "Medium (10-100MB)": (10*1024*1024, 100*1024*1024),
            "Large (100MB-1GB)": (100*1024*1024, 1024*1024*1024),
            "Huge (>1GB)": (1024*1024*1024, float('inf'))
        }
        
        for files in self.file_details.values():
            for file_info in files:
                file_size = file_info['size']
                
                # Determine size category
                size_category = "Others"
                for category, (min_size, max_size) in size_categories.items():
                    if min_size <= file_size < max_size:
                        size_category = category
                        break
                
                # Create size folder if it doesn't exist
                target_folder = os.path.join(folder, size_category)
                os.makedirs(target_folder, exist_ok=True)
                
                # Move file
                src = file_info['path']
                dst = os.path.join(target_folder, file_info['name'])
                
                try:
                    shutil.move(src, dst)
                    processed += 1
                    progress = processed / total_files
                    self.progress_bar.set(progress)
                    self.update_idletasks()
                except Exception as e:
                    print(f"Error moving {src}: {e}")
        
        messagebox.showinfo("Success", f"Organized {processed} files by size")
        self.progress_bar.set(0)
        self.scan_folder()
    
    def unorganize_files(self):
        folder = self.folder_path.get()
        if not folder or not os.path.exists(folder):
            messagebox.showerror("Error", "Please select a valid folder first")
            return
        
        # Confirm with user
        if not messagebox.askyesno("Confirm", "This will move all files to the root folder and delete empty subfolders. Continue?"):
            return
        
        total_files = 0
        moved_files = 0
        
        # First count all files
        for root, _, files in os.walk(folder):
            total_files += len(files)
        
        # Move all files to root folder
        for root, dirs, files in os.walk(folder, topdown=False):
            for name in files:
                src = os.path.join(root, name)
                dst = os.path.join(folder, name)
                
                # Handle name conflicts
                counter = 1
                while os.path.exists(dst):
                    base, ext = os.path.splitext(name)
                    dst = os.path.join(folder, f"{base}_{counter}{ext}")
                    counter += 1
                
                try:
                    shutil.move(src, dst)
                    moved_files += 1
                    progress = moved_files / total_files if total_files > 0 else 0
                    self.progress_bar.set(progress)
                    self.update_idletasks()
                except Exception as e:
                    print(f"Error moving {src}: {e}")
            
            # Remove empty directories
            for name in dirs:
                dir_path = os.path.join(root, name)
                try:
                    os.rmdir(dir_path)
                except OSError:
                    pass  # Directory not empty
        
        messagebox.showinfo("Success", f"Moved {moved_files} files to root folder and removed empty subfolders")
        self.progress_bar.set(0)
        self.scan_folder()
    
    def compress_files(self):
        folder = self.folder_path.get()
        if not folder or not os.path.exists(folder):
            messagebox.showerror("Error", "Please select a valid folder first")
            return
        
        # Ask for compression format
        format_choice = self.compression_format.get().lower()
        
        # Create zip file name based on folder name
        zip_name = os.path.basename(folder.rstrip(os.sep)) + ".zip"
        zip_path = os.path.join(os.path.dirname(folder), zip_name)
        
        # Confirm with user
        if not messagebox.askyesno("Confirm", f"Create {zip_name} with all files in this folder?"):
            return
        
        try:
            with zipfile.ZipFile(zip_path, 'w', zipfile.ZIP_DEFLATED) as zipf:
                for root, _, files in os.walk(folder):
                    for file in files:
                        file_path = os.path.join(root, file)
                        arcname = os.path.relpath(file_path, folder)
                        zipf.write(file_path, arcname)
            
            messagebox.showinfo("Success", f"Created {zip_name} successfully")
        except Exception as e:
            messagebox.showerror("Error", f"Failed to create zip file: {e}")
    
    def on_closing(self):
        self.running = False
        self.destroy()

if __name__ == "__main__":
    app = FileOrganizerApp()
    app.mainloop()