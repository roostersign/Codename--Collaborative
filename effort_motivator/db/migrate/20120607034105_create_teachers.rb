class CreateTeachers < ActiveRecord::Migration
  def change
    create_table :teachers do |t|
      t.string :name
      t.string :password
      t.string :pic
      t.text :profile
      t.float :cash

      t.timestamps
    end
  end
end
